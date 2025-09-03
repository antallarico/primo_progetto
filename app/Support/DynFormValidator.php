<?php

namespace App\Support;

use App\Models\ModelloDinamico;

class DynFormValidator
{
    /**
     * Valida e normalizza il payload rispetto allo schema del modello.
     * @return array{0: array, 1: array} [$clean, $errors] con chiavi tipo "payload.campo"
     */
    public static function validate(ModelloDinamico $modello, array $payload): array
    {
        $fields = $modello->schema_json['fields'] ?? [];
        $errors = [];
        $clean  = [];

        $isBlank = function ($v) {
            return $v === null || $v === '' || (is_array($v) && count($v) === 0);
        };
        $normType = function ($t) {
            return strtolower(str_replace([' ', '-'], '_', (string)($t ?? 'text')));
        };
        $inOptions = function ($val, $opts): bool {
            $opts = is_array($opts) ? $opts : [];
            foreach ($opts as $o) {
                $candidate = is_array($o) ? (string)($o['value'] ?? $o['val'] ?? '') : (string)$o;
                if ((string)$val === $candidate) return true;
            }
            return false;
        };
        $optionsList = function ($opts): array {
            $out = [];
            foreach ((array)$opts as $o) {
                $out[] = is_array($o) ? (string)($o['value'] ?? $o['val'] ?? '') : (string)$o;
            }
            return $out;
        };
        $wrapRegex = function (string $pattern): ?string {
            // Scegli un delimitatore che non compare nel pattern
            foreach (['~', '#', '%', '/'] as $delim) {
                if (strpos($pattern, $delim) === false) {
                    $rx = $delim . $pattern . $delim . 'u';
                    // Test rapido di compilazione
                    set_error_handler(function(){});
                    $ok = @preg_match($rx, '') !== false;
                    restore_error_handler();
                    return $ok ? $rx : null;
                }
            }
            return null;
        };

        foreach ($fields as $f) {
            $key = $f['key'] ?? null;
            if (!$key) continue;

            $type = $normType($f['type'] ?? 'text');
            $req  = (bool)($f['required'] ?? false);
            $vld  = $f['validations'] ?? [];
            $val  = $payload[$key] ?? null;

            // Default: se non required e blank, applica default se presente
            if (!$req && $isBlank($val) && array_key_exists('default', $f)) {
                $val = $f['default'];
            }

            // Required generico
            if ($req && $isBlank($val) && $type !== 'checkbox') {
                $errors["payload.$key"] = 'Campo obbligatorio';
                continue;
            }

            switch ($type) {
                case 'radio':
                case 'select': {
                    if ($isBlank($val)) { $clean[$key] = null; break; }
                    $val = (string)$val;
                    if (!$inOptions($val, $f['options'] ?? [])) {
                        $errors["payload.$key"] = 'Valore non consentito';
                        break;
                    }
                    $clean[$key] = $val;
                    break;
                }

                case 'multi_select': {
                    $arr = is_array($val) ? $val : (strlen((string)$val) ? explode(',', (string)$val) : []);
                    $arr = array_values(array_unique(array_map('strval', array_map('trim', $arr))));
                    $allowed = $optionsList($f['options'] ?? []);
                    $arr = array_values(array_filter($arr, fn($x) => in_array($x, $allowed, true)));
                    if ($req && !count($arr)) { $errors["payload.$key"] = 'Seleziona almeno un elemento'; break; }
                    $clean[$key] = $arr;
                    break;
                }

                case 'checkbox': {
                    // Se required → deve essere "vero"
                    $isTrue = ($val === true || $val === 1 || $val === '1' || $val === 'on');
                    if ($req && !$isTrue) {
                        $errors["payload.$key"] = 'Devi selezionare questa casella';
                        break;
                    }
                    $clean[$key] = $isTrue ? 1 : 0;
                    break;
                }

                case 'number':
                case 'range': {
                    if ($isBlank($val)) { $clean[$key] = null; break; }
                    if (!is_numeric($val)) { $errors["payload.$key"] = 'Deve essere un numero'; break; }
                    $num = (float)$val;
                    if (isset($vld['min']) && $num < (float)$vld['min']) { $errors["payload.$key"] = 'Minimo: '.$vld['min']; break; }
                    if (isset($vld['max']) && $num > (float)$vld['max']) { $errors["payload.$key"] = 'Massimo: '.$vld['max']; break; }
                    if (isset($vld['step'])) {
                        $min  = isset($vld['min']) ? (float)$vld['min'] : 0.0;
                        $step = (float)$vld['step'];
                        if ($step > 0) {
                            $q = ($num - $min) / $step;
                            if (abs($q - round($q)) > 1e-9) { $errors["payload.$key"] = 'Valore non allineato allo step '.$step; break; }
                        }
                    }
                    $clean[$key] = $num;
                    break;
                }

                case 'email': {
                    if ($isBlank($val)) { $clean[$key] = null; break; }
                    $s = trim((string)$val);
                    if (!filter_var($s, FILTER_VALIDATE_EMAIL)) { $errors["payload.$key"] = 'Email non valida'; break; }
                    if (!empty($vld['pattern'])) {
                        $rx = $wrapRegex($vld['pattern']);
                        if ($rx && preg_match($rx, $s) !== 1) { $errors["payload.$key"] = 'Formato email non valido (pattern)'; break; }
                    }
                    $clean[$key] = $s;
                    break;
                }

                case 'url': {
                    if ($isBlank($val)) { $clean[$key] = null; break; }
                    $s = trim((string)$val);
                    if (!filter_var($s, FILTER_VALIDATE_URL)) { $errors["payload.$key"] = 'URL non valido'; break; }
                    $clean[$key] = $s;
                    break;
                }

                case 'tel': {
                    if ($isBlank($val)) { $clean[$key] = null; break; }
                    $s = trim((string)$val);
                    if (!empty($vld['pattern'])) {
                        $rx = $wrapRegex($vld['pattern']);
                        if ($rx && preg_match($rx, $s) !== 1) { $errors["payload.$key"] = 'Numero non valido (pattern)'; break; }
                    } else {
                        if (!preg_match('/^[0-9\s\+\-\(\)\/\.]{3,}$/', $s)) { $errors["payload.$key"] = 'Numero non valido'; break; }
                    }
                    $clean[$key] = $s;
                    break;
                }

                case 'date': {
                    if ($isBlank($val)) { $clean[$key] = null; break; }
                    $s = (string)$val;
                    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $s)) { $errors["payload.$key"] = 'Data non valida (YYYY-MM-DD)'; break; }
                    $clean[$key] = $s;
                    break;
                }

                case 'time': {
                    if ($isBlank($val)) { $clean[$key] = null; break; }
                    $s = (string)$val;
                    if (!preg_match('/^\d{2}:\d{2}(:\d{2})?$/', $s)) { $errors["payload.$key"] = 'Ora non valida (HH:MM)'; break; }
                    $clean[$key] = substr($s, 0, 5);
                    break;
                }

                case 'datetime': {
                    if ($isBlank($val)) { $clean[$key] = null; break; }
                    $s = str_replace(' ', 'T', trim((string)$val));
                    // Accetta anche i secondi, normalizza a minuti
                    if (!preg_match('/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}(:\d{2})?$/', $s)) { $errors["payload.$key"] = 'Data/ora non valida (YYYY-MM-DDTHH:MM)'; break; }
                    $clean[$key] = substr($s, 0, 16);
                    break;
                }

                case 'textarea':
                case 'text':
                default: {
                    if ($isBlank($val)) { $clean[$key] = null; break; }
                    $s = (string)$val;
                    if (isset($vld['minLength']) && mb_strlen($s) < (int)$vld['minLength']) { $errors["payload.$key"] = 'Lunghezza minima: '.$vld['minLength']; break; }
                    if (isset($vld['maxLength']) && mb_strlen($s) > (int)$vld['maxLength']) { $errors["payload.$key"] = 'Lunghezza massima: '.$vld['maxLength']; break; }
                    if (!empty($vld['pattern'])) {
                        $rx = $wrapRegex($vld['pattern']);
                        if ($rx && preg_match($rx, $s) !== 1) { $errors["payload.$key"] = 'Formato non valido (pattern)'; break; }
                    }
                    $clean[$key] = $s;
                    break;
                }
            }
        }

        return [$clean, $errors];
    }
}
