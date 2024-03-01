### Step 1
Clone the repo locally and cd into project, run composer install and copy .env.example to .env

### Step 2
We're using laravel sail for local development

Run `./vendor/bin/sail up -d`

If you are using a mac, you might run into issues with the platform parameter in docker-compose file. Simply add this to docker-compose `platform: linux/amd64` It should hopefully resolve issue 

### Step 3

In your .env file you will find QUOTES_DRIVER key set to kanye, you can remove that key entirely, or change it to another quotes provider. To demonstrate manager pattern I've provided another implementation called `other`. Just set `QUOTES_DRIVER=other` 

Additionally, please look at `config/quotes.php` for more details


### Step 4

Run `php artisan migrate` or `./bin/vendor/sail migrate` or `./bin/vendor/sail php artisan migrate`

----
In order to access API you will need a token, mind that generated tokens expire in 60 minutes from creation

Run command `php artisan quotes:generate-token` 

You will be provided with token. 

### Step 5

Use api: 

`/quotes?token=YOUR_TOKEN`

`/quotes/refresh?token=YOUR_TOKEN`


### Step 6

Run all tests `./bin/vendor/phpunit tests`

Enjoy! 

----

### Additional Information

##### Developers Tip:

You can use Facade to retrieve quotes like so:

`Quotes::get()`

You can even specify manager

`Quotes::using('kanye')->get()`

`Quotes::using('other')->get()`

---

#### Interesting Stuff

In the modules/Quote you will find `Quotes` and `QuotesConcrete` files, this is nothing more but a facade pattern to keep the code nice and clean when used.

This is also my way of keeping the module independent from other modules, if say another module was to use Quotes module, it has to do so via Facade only! The rest of the code within the module remains isolated. With that in mind, should we wish to extract the module into a micro service, we would only need to update functions within the facade to call the service instead


### Final Words Of Wisdom

I hope I haven't missed anything, feel free to give me a shout should anythign be unclear - or missing in fact haha. Peace!
