REPOSITORY TEMPLATE:
- npm install
- composer install

AVVIO SERVER:
- php artisan serve
- npm run dev

CREAZIONE MODEL, CONTROLLER CON TUTTE LE FUNZIONI, MIGRATION E SEEDER:
php artisan make:model User --resource -cms

CREAZIONE MODEL, CONTROLLER, MIGRATION E SEEDER:
php artisan make:model User -cms

MIGRATION:
creazione tabella DB:
php artisan make:migration create_users_table

esecuzione migration:
php artisan migrate

annulla l'ultimo migration:
php artisan migrate:rollback

aggiungere/modificare tabella:
php artisan make:migration update_qualcosa_to_users_table

CREAZIONE MODEL:
php artisan make:model User
User = il nome deve essere lo stesso della tabella del DB a cui si riferisce, però al singolare, in PascalCase.

CREAZIONE SEEDER:
php artisan make:seeder UsersTableSeeder 
Il nome deve essere in PascalCase, iniziare con il nome della tabella che vogliamo popolare e finire con TableSeeder

eseguire il seeder:
php artisan db:seed UsersTableSeeder

CREAZIONE CONTROLLER:
php artisan make:controller UserController
Il nome deve essere al singolare, in PascalCase e seguito dalla parola Controller.

AUTO COMPILARE CON TUTTE LE FUNZIONI:
php artisan make:controller --resource UserController

VISUALIZZARE LA LISTA DELLE ROUTE
php artisan route:list

--------FUNZIONI NEL CONTROLLER---------------
----INDEX & SHOW----

1) - INDEX ex:
    public function index(){
        $comics = Comic::all();
        return view("comic.index", ["comics"=>$comics]);
    }
    

2) - SHOW -- uso l'id per pescare l'oggetto /1, /2, /3 , ... (sarebbe {comic}) ex:
    public function show($id) {
        $comics = Comic::findOrFail($id);
        return view("comic.show", ["comics" =>$comics]);
    }

--------------------
----CREATE & STORE----

3) - CREATE -- ex:
    public function create(){
        return view("comic.create");
    }

4) - STORE -- Legge i dati inviati dal form con request. ex:

    public function store(Request $request) {
        
        //Controlliamo i dati che riceviamo con il formato che vogliamo.
        //Troviamo i parametri di validazione su Laravel Validation.
        $data = $request->validate ([
            "title"=>"required|string",
            "description"=>"required|string",
            "thumb"=>"nullable|string",
            "price"=>"required|decimal:2,6",
            "series"=>"required|string",
            "sale_date"=>"nullable|date",
            "type"=>"nullable|string",
            "artists"=>"nullable|string",
            "writers"=>"nullable|string",
            ]);
          
        $data["artists"] = json_encode([$data["artists"]]);
        $data["writers"] = json_encode([$data["writers"]]);

        $newComic = new Comic();
        $newComic->fill($data);

        //Salviamo i file nel database
        $newComic->save();
        
        //Rimandiamo l'utente su un'altra pagina che vogliamo dopo aver salvato i dati;
        //Se non viene fatto, l'utente può inviare gli stessi dati più volte, cosa che NON vogliamo:
        return redirect()->route('comic.index');
    }

----------------------
----EDIT & UPDATE----

5) - EDIT ex:
    public function edit($id) {
        $comics = Comic::findOrFail($id);
        return view('comic.edit', ["comic"=> $comics]);
    }

6) - UPDATE ex:
    public function update( Request $request, $id) {

        $comics = Comic::findOrFail($id);

        $data = $request->validate ([
            "title"=>"required|string",
            "description"=>"required|string",
            "thumb"=>"nullable|string",
            "price"=>"required|decimal:2,6",
            "series"=>"required|string",
            "sale_date"=>"nullable|date",
            "type"=>"nullable|string",
            "artists"=>"nullable|string",
            "writers"=>"nullable|string",
            ]);

            $data["artists"] = json_encode([$data["artists"]]);
            $data["writers"] = json_encode([$data["writers"]]);

            //Si comporta coem un fill() + save();
            $comics->update($data);

            //Come per lo store, facciamo un redirect;
            return redirect()->route('comic.show', $comics->id);
    } 


---------------------
----DESTROY & TRASH----
NOTA: Questi due esempi si rifanno al caso particolare della cartella laravel-dc-comics

7) - TRASH ex:
    public function trash() {
        $comics = Comic::onlyTrashed()->get();
        return view("comic.trash", ["comics" => $comics]);
    }

8) - DESTROY ex:
    //Inserisco il request per recuperare i dati per il force delete
    public function destroy(Request $request, $id){

        if ($request->input("force")) {
            $comics = Comic::onlyTrashed()->where("id", $id)->first();
            //Force delete (permanente)
            $comics->forceDelete();
        }else {
            $comics = Comic::findOrFail($id);
            //Soft delete (non permanente -> trash)
            $comics->delete();
        }

        return redirect()->route('comic.index');
    }

-----------------------
---------------------------------------


--------FUNZIONI NEL MODEL---------------
----SOFTDELETES----
    -Si aggiunge vicino accanto a "use HasFactory;"
    -SoftDeletes, per funzionare, ha però bisogno una colonna "deleted_at"
    QUINDI dobbiamo creare una migration  add_soft_delete_to_nometabella_table:
      php artisan make:migration add_soft_delete_to_nometabella_table
    è possibile anche ripristinarlo #Restoring Soft Deleted Models su Laravel.
    Se voglio cancellarlo definitivamente, posso fare il forceDelete()(cerca Laravel)
    che vediamo nella funzione destroy nel Controller;

----CASTS----
      -Mi permette di convertire i dati delle colonne in tipologie differenti:
      ex.
    protected $casts = [
        "sale_date"=>"date",
    ];
   -------------

----FILLABLE---- (serve per la funzione $fill che troviamo nella funzione STORE in controller);
   Al suo interno inseriamo le colonne che vogliamo popolare per la funzione fill() o update(). Ex:

    protected $fillable = [
        "title",
        "description",
        "thumb",
        "price",
        "series",
        "sale_date",
        "type",
        "artists",
        "writers",
    ];
   ---------------
   -----------------------------------------


-----ROUTES IN web.php-----
Le routes sono praticamente le connessioni che il browser ha con le nostre pagine del sito.
Ne creiamo quante necessarie, solitamente una per ogni funzione del controller:

--CREATE--
  Route::get("/comic/create", [ComicController::class, "create"])->name("comic.create"); - Indirizza ad una pagina con form per inserire i dati;
--
  Route::post("/comic", [ComicController::class, "store"])->name("comic.store"); - Rotta di dove verranno inviati i dati. Essa è in POST.
----------

--READ--
  Route::get('/comic', [ComicController::class, "index"])->name("comic.index"); - Pagina in cui stamperemo l'anteprima degli elementi;
--
  Route::get('/comic/{comic}', [ComicController::class, "show"])->name("comic.show"); - Pagina in cui stamperemo i dettagli di un elemento;
--------

--UPDATE--
  Route::get('/comic/{comic}/edit', [ComicController::class, "edit"])->name("comic.edit");
-- 
  Route::match(["patch","put"], '/comic/{comic}/update', [ComicController::class, "update"])->name("comic.update");
    L'update possiamo anche scriverlo solo come:
    >Route::patch('/comic/{comic}', [ComicController::class, "update"])->name("comic.update");
    oppure:
    >Route::put('/comic/{comic}', [ComicController::class, "update"])->name("comic.update");
    Vanno bene entrambe. L'importante è che se ne usi una.
----------

--DESTROY & TRASH--
ATTENZIONE a destroy: il metodo è DELETE e anche sul controller è $comics->delete(); 
  Route::delete('/comic/{id}', [ComicController::class, "destroy"])->name("comic.destroy");
--
  Route::get("/comic/trash", [ComicController::class, "trash"])->name("comic.trash"); - Pagina del cestino in cui vanno gli elementi cancellati
  che possiamo poi cancellare definitivamente o, implementandolo, ripristinare;
-------------------