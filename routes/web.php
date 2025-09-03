<?php

use Illuminate\Support\Facades\Route;
use App\Models\Lavoratore;
use App\Http\Controllers\Formazione\FormazioneCorsoController;
use App\Http\Controllers\Formazione\SessioneController;
use App\Http\Controllers\Formazione\FormazioneController;
use App\Http\Controllers\Formazione\StoricoLavoratoreController;
use App\Http\Controllers\Formazione\LavoratoriReportController;
use App\Http\Controllers\LavoratoreController;
use App\Http\Controllers\ModelliDinamici\ModelloDinamicoController;
use App\Http\Controllers\Manutenzioni\RegistroController;
use App\Http\Controllers\Manutenzioni\TipologiaController;
use App\Http\Controllers\Attrezzature\AttrezzaturaController;
use App\Http\Controllers\Attrezzature\AttrezzaturaTipologiaController;
//use App\Http\Controllers\Attrezzature\SchedeController; // già creato
use App\Http\Controllers\Addestramenti\TipologieController as AddTipologieController;
use App\Http\Controllers\Addestramenti\RegistroController   as AddRegistroController;


// --- Modelli dinamici (CRUD + JSON + Builder) ------------------------------
Route::prefix('modelli-dinamici')->name('modelli_dinamici.')->group(function () {
    Route::get('/',                     [ModelloDinamicoController::class, 'index'])->name('index');
    Route::get('/create',               [ModelloDinamicoController::class, 'create'])->name('create');
    Route::post('/',                    [ModelloDinamicoController::class, 'store'])->name('store');
    Route::get('/{modelloDinamico}/edit',[ModelloDinamicoController::class, 'edit'])->name('edit');
    Route::put('/{modelloDinamico}',    [ModelloDinamicoController::class, 'update'])->name('update');
    Route::delete('/{modelloDinamico}', [ModelloDinamicoController::class, 'destroy'])->name('destroy');
	
	// endpoint usato dal mount-by-select
	Route::get('/{id}/json',              [ModelloDinamicoController::class, 'showJson'])->name('json');
    
	// builder (pagina di editing visuale)
    Route::get('/{id}/builder',           [ModelloDinamicoController::class, 'builder'])->name('builder');
});

// Gestione Manutenzioni	
Route::prefix('manutenzioni')->name('manutenzioni.')->group(function () {
    Route::resource('competenze', \App\Http\Controllers\Manutenzioni\CompetenzaController::class);
});
Route::prefix('manutenzioni')->name('manutenzioni.')->group(function () {
    Route::resource('programmate', \App\Http\Controllers\Manutenzioni\ProgrammataController::class);
});
Route::prefix('manutenzioni')->name('manutenzioni.')->group(function () {
    Route::resource('registro', \App\Http\Controllers\Manutenzioni\RegistroController::class);
});

// Gestione Manutenzioni manutenzioni_tipologie e manutenzioni_checklist_dinamiche
Route::prefix('manutenzioni')->name('manutenzioni.')->group(function () {
    Route::resource('tipologie', TipologiaController::class);
});


// CRUD Attrezzature
Route::resource('attrezzature', AttrezzaturaController::class)
	->parameters(['attrezzature' => 'attrezzatura'])
	->whereNumber('attrezzatura')
	->except('show')
	->names('attrezzature');
//Route::resource('attrezzature', AttrezzaturaController::class)->names('attrezzature');

// pagina di supporto per dati mancanti
Route::get('attrezzature/quadro', [AttrezzaturaController::class, 'quadro'])->name('attrezzature.quadro');

// CRUD Tipologie Attrezzature
Route::prefix('attrezzature/tipologie')->name('attrezzature.tipologie.')->group(function () {
    Route::get('/', [AttrezzaturaTipologiaController::class, 'index'])->name('index');
    Route::get('/create', [AttrezzaturaTipologiaController::class, 'create'])->name('create');
    Route::post('/', [AttrezzaturaTipologiaController::class, 'store'])->name('store');
    Route::get('/{tipologia}/edit', [AttrezzaturaTipologiaController::class, 'edit'])->name('edit');
    Route::put('/{tipologia}', [AttrezzaturaTipologiaController::class, 'update'])->name('update');
    Route::delete('/{tipologia}', [AttrezzaturaTipologiaController::class, 'destroy'])->name('destroy');
});
/*
// Scheda dinamica per singola attrezzatura (pattern condiviso col Registro)
Route::prefix('attrezzature/{attrezzatura}')->name('attrezzature.')->group(function () {
    Route::get('scheda',        [SchedeController::class, 'show'])->name('schede.show');
    Route::get('scheda/create', [SchedeController::class, 'create'])->name('schede.create');
    Route::post('scheda',       [SchedeController::class, 'store'])->name('schede.store');
    Route::get('scheda/edit',   [SchedeController::class, 'edit'])->name('schede.edit');
    Route::put('scheda',        [SchedeController::class, 'update'])->name('schede.update');
});
*/


// Addestramenti - Tipologie
Route::prefix('addestramenti/tipologie')->name('addestramenti.tipologie.')->group(function () {
    Route::get('/',        [AddTipologieController::class, 'index'])->name('index');
    Route::get('/create',  [AddTipologieController::class, 'create'])->name('create');
    Route::post('/',       [AddTipologieController::class, 'store'])->name('store');
    Route::get('/{tipologia}/edit', [AddTipologieController::class, 'edit'])->name('edit');
    Route::put('/{tipologia}',      [AddTipologieController::class, 'update'])->name('update');
    Route::delete('/{tipologia}',   [AddTipologieController::class, 'destroy'])->name('destroy');
});

// Addestramenti - Registro
Route::prefix('addestramenti/registro')->name('addestramenti.registro.')->group(function () {
    Route::get('/',        [AddRegistroController::class, 'index'])->name('index');
    Route::get('/create',  [AddRegistroController::class, 'create'])->name('create');
    Route::post('/',       [AddRegistroController::class, 'store'])->name('store');
    Route::get('/{addestramento}/edit', [AddRegistroController::class, 'edit'])->name('edit');
    Route::put('/{addestramento}',      [AddRegistroController::class, 'update'])->name('update');
    Route::delete('/{addestramento}',   [AddRegistroController::class, 'destroy'])->name('destroy');
});


	
Route::get('/lavoratori', function () {
    $lavoratori = Lavoratore::orderBy('cognome')->orderBy('nome')->get();
    return view('lavoratori.index', compact('lavoratori'));
});


// Formazione-corsi
Route::prefix('formazione/corsi')->name('formazione.corsi.')->group(function () {
    Route::get('/', [FormazioneCorsoController::class, 'index'])->name('index');
    Route::get('/create', [FormazioneCorsoController::class, 'create'])->name('create');
    Route::post('/', [FormazioneCorsoController::class, 'store'])->name('store');
    Route::get('/{corso}/edit', [FormazioneCorsoController::class, 'edit'])->name('edit');
    Route::put('/{corso}', [FormazioneCorsoController::class, 'update'])->name('update');
    Route::delete('/{corso}', [FormazioneCorsoController::class, 'destroy'])->name('destroy');
});
// Formazione-sessioni (eventi/lezioni/corsi/attività formative svolte)
Route::prefix('formazione/sessioni')->name('formazione.sessioni.')->group(function () {
    Route::get('/', [SessioneController::class, 'index'])->name('index');
    Route::get('/create', [SessioneController::class, 'create'])->name('create');
    Route::post('/', [SessioneController::class, 'store'])->name('store');
    Route::get('/{sessione}/edit', [SessioneController::class, 'edit'])->name('edit');
    Route::put('/{sessione}', [SessioneController::class, 'update'])->name('update');
    Route::delete('/{sessione}', [SessioneController::class, 'destroy'])->name('destroy');
});
// Formazione Registro partecipazioni alle sessioni
Route::prefix('formazione/registro')->name('formazione.registro.')->group(function () {
    Route::get('/{sessione_id}', [\App\Http\Controllers\Formazione\FormazioneController::class, 'index'])->name('index');
    Route::get('/{sessione_id}/create', [\App\Http\Controllers\Formazione\FormazioneController::class, 'create'])->name('create');
    Route::post('/{sessione_id}', [\App\Http\Controllers\Formazione\FormazioneController::class, 'store'])->name('store');
    Route::get('/partecipazione/{id}/edit', [\App\Http\Controllers\Formazione\FormazioneController::class, 'edit'])->name('edit');
    Route::put('/partecipazione/{id}', [\App\Http\Controllers\Formazione\FormazioneController::class, 'update'])->name('update');
    Route::delete('/partecipazione/{id}', [\App\Http\Controllers\Formazione\FormazioneController::class, 'destroy'])->name('destroy');
});

// Storico formazione del lavoratore
Route::get('/formazione/storicolavoratore/{lavoratore}', [\App\Http\Controllers\Formazione\FormazioneController::class, 'storicolavoratore'])
    ->name('formazione.storicolavoratore');

// Elenco lavoratori per Storico formazione
Route::get('/formazione/lavoratori', [FormazioneController::class, 'lavoratori'])->name('formazione.lavoratori');
// Storico formazione per corso
//Route::get('/formazione/storicocorso/{corso}', [FormazioneController::class, 'storicocorso'])->name('formazione.storicocorso');
Route::get('/formazione/storicocorso/{corso_id}', [FormazioneController::class, 'storicocorso'])->name('formazione.storicocorso');


// Dashboard iniziale
Route::get('/', function () {
    return view('dashboard');
})->name('dashboard');

