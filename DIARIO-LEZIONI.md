# Argomenti delle lezioni e riferimenti

I vari comandi di setup del progetto e del container sono quelli usati a lezione. La branch master viene aggiornata dopo
ogni lezione (salvo imprevisti). Se si è saltata una lezione o si è rimasti indietro, molti di questi comandi possono
essere sostituiti da:
1) Aggiornare il repository locale con le modifiche della lezione

   A seconda di quanto siete ferrati con git, potete lanciare `git pull origin master` e poi risolvere tutti i conflitti
   che inevitabilmente ci saranno, oppure potete abbandonare la vostra branch e passare direttamente a quella della
   lezione con
   ```shell
   $ git fetch origin <nome della branch>
   $ git switch <nome della branch>
   ```
2) Avviare l'ambiente
   ```shell
   $ make start
   ```
3) Aprire una shell all'interno del container
   ```shell
   $ make shell
   ```
4) Aggiornare le dipendenze tramite composer
   ```shell
   # all'interno del container
   $ composer install --dev
   ```

## Lezione 1 - Configurazione dell'ambiente
Durante questa lezione abbiamo configurato l'ambiente e installato Symfony (il binario) e con esso poi il framework
Symfony.

Su linux, per evitare di dover usare `sudo` per lanciare i comandi docker, seguire [questa guida](https://docs.docker.com/engine/install/linux-postinstall/#manage-docker-as-a-non-root-user)

Lista dei comandi usati:
```shell
$ make start
# o in alternativa, senza usare make
$ docker compose up -d
# verificare su localhost:8080 la presenza di una pagina web, poi rimuovere la cartella public e il suo contenuto
$ make shell
# o senza make
$ docker exec -it web bash
# da questo momento i comandi vanno lanciati all'interno del container
$ git config --global user.email "example@example.com"
wget https://get.symfony.com/cli/installer -O - | bash
# utlizzando il binario di symfony appena installato
$ symfony new --no-git --version=^6.4
```

Argomenti toccati a lezione:
- make,
- Dockerfile e docker-compose.yml,
- elementi di configurazione di Apache,
- cos'è semantic versioning ([semver](https://semver.org)),
- composer come package manager e come autoloader,
- ambienti di esecuzione di symfony (dev, test, prod),
- struttura delle directory del progetto,
- cosa sono i symfony bundles,
- installazione di PHPStorm e dei plugin PHP Toolbox, Symfony.

La [Branch "lesson-one-end"](https://github.com/RBastianini/labingsoft/tree/lesson-one-end) contiene lo stato del
  repository alla fine della lezione.


## Lezione 2 - Controller, rotte, view, toolbar di debug e traduzione

Durante questa lezione abbiamo iniziato a vedere il pattern MVC, nello specifico le parti VIEW e CONTROLLER
Abbiamo iniziato creando alcuni controller, alcune action e associando ad esse delle rotte utilizzando gli attributi
PHP.
Abbiamo visto come mostrare le rotte tramite la console di symfony
```shell
# Dall'interno del container
$ bin/console debug:router
```
come cambiare nome alle rotte e come ottenere dei parametri tramite porzioni del path delle rotte.

Inizialmente abbiamo restituito dell'html scritto manualmente tramite degli oggetti Response dalle action dei controller
e accennato ai pericoli del [XSS](https://owasp.org/www-community/attacks/xss/).

Abbiamo installato php-cs-fixer e PHPStan e accennato al funzionamento dell'analisi statica.

```shell
# Dall'interno del container
$ composer require --dev cs-fixer-shim
$ composer require --dev phpstan/phpstan
$ composer require --dev phpstan/phpstan-beberlei-assert
```
e usato entrambi gli strumenti per controllare il codice scritto e per correggerlo
```shell
# Dall'interno del container
$ vendor/bin/php-cs-fixer check -v
$ vendor/bin/php-cs-fixer fix
$ vendor/bin/phpstan analyse
```
.

Abbiamo quindi installato twig
```shell
# Dall'interno del container
$ composer install twig
```
e notato come alcuni pacchetti abbiano un nome composto da due parti separate da uno slash, e altri no. Abbiamo quindi
accennato a [packagist](https://packagist.org/) e a come Symfony fornisce degli alias per alcuni pacchetti di uso comune
([qui](https://github.com/symfony/recipes) la lista degli alias disponibili).

Abbiamo quindi modificato i controller definiti prima per utilizzare twig per renderizzare le risposte, utilizzando il
metodo `render()` di `AbstractController`. Abbiamo visto la sintassi `{% %}` e `{{ }}` di twig.
Abbiamo aggiunto asset-mapper per gestire gli asset frontend
```shell
# Dall'interno del container
$ composer install asset-mapper
```
e utilizzato lo stesso per aggiungere bootstrap ai template
```shell
# Dall'interno del container
$ bin/console importmap:require bootstrap
```
 
Abbiamo installato il profiler di Symfony e fatto un tour della toolbar di debug
```shell
# Dall'interno del container
$ composer require --dev profiler
```
Infine, abbiamo aggiunto il pacchetto di traduzione e visto come tradurre le stringhe dei template.
```shell
# Dall'interno del container
$ composer require translation
```

### Da fare per casa
Fare in modo che sia possibile navigare verso `localhost:8080/weather/STATO/CITTÀ` dove `STATO` è un codice di 2 lettere
che rappresenta una nazione e `CITTÀ` è il nome di una città. Quando un utente richiede questa pagina, deve essere
restituita una risposta renderizzata con twig, usando la stessa struttura dei file vista a lezione, contenente il testo:
"Ecco le previsioni del tempo per CITTÀ (STATO)", dove CITTÀ e STATO devono variare sulla base dell'URL della richiesta.
Esempio:
Se l'utente naviga verso `localhost:8080/weather/IT/Roma`, la pagina dovrà contenere "Ecco le previsioni del tempo per
Roma (IT)".

### Riferimenti
- https://symfony.com/doc/6.4/routing.html
- https://symfony.com/doc/6.4/templates.html
- https://symfony.com/doc/6.4/controller.html
- https://symfony.com/doc/6.4/frontend/asset_mapper.html
- https://symfony.com/doc/6.4/translation.html#translations-in-templates

La [Branch "lesson-two-end"](https://github.com/RBastianini/labingsoft/tree/lesson-two-end) contiene lo stato del
repository alla fine della lezione.
