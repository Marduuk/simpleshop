###Słowo wstępu
Aplikacja jest napisana w możliwie łatwy sposób. Starałem się jak najlepiej wykorzystać narzędzia symfony, nie wiem czy dobrze mi to wyszło.
Ustawianie waluty na podstawie locale projektu na pewno można zrobić lepiej, jednak nie znalazłem innego sposobu. 
Kolejną rzeczą, do której miałbym wątpliwości, jest walidacja, o ile w formularzu tworzącym 
nowy produkt nie miałem z tym problemu, o tyle
w przypadku formularza logowania się nie mogłem znaleźć odpowiedniego sposobu na zwracanie errorów do klienta z poziomu 
```AppAuthAuthenticator```
, dlatego napisałem swój prosty validator. 
 W zadaniu poproszony byłem o użycie CQS niestety nie wyrobiłem się czasowo a i same podejście średnio znam.
 Oprócz tych trzech rzeczy wydaje mi się ze aplikację napisałem okay.


###Apka

Jako bazę danych postanowiłem użyć SQLite'a.
 Locale jest ustawiane na podstawie .env tak samo jak MAILER_DSN, na potrzeby projektu używałem mailtrapa.


Przed odpaleniem apki należy wykonać następujące komendy:

```composer install```

Następnie odpalić migracje:

```php bin/console make:migration```

```php bin/console doctrine:migrations:migrate```

Aby załadować fixtures

```php bin/console doctrine:fixtures:load```

Aby odpalić testy:

```bin/phpunit tests/```

Odpalenie servera:

```php bin/console server:start```

Mam nadzieje ze niczego nie pominąłem.

Pozdrawiam, 
Kacper

