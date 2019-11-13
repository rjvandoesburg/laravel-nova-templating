# Add dynamic JS template loading to your Laravel Nova powered application 

Let your Nova resources decide what front-end template needs to be loaded.

This package was inspired by [WordPress template hierarchy](https://wphierarchy.com/).

This package will add an endpoint to your application and uses Nova to resolve the resource and associated model.
The endpoint will return an array of names e.g. a hierarchy of templates that you can use in your front-end application to "dynamically" render a page.

## Requirements

This package requires Laravel 5.8 or higher, PHP 7.2 or higher.

## Installation

You can install the package via composer:

``` bash
composer require rjvandoesburg/laravel-nova-templating
```

To add the endpoint, add the following to a routes file, you can apply your own groups & middleware as you see fit!
```php
Route::novaResourceTemplateRoute();
```
This will add `/template-api/{resource}/{resourceId}` to your application.

The package will automatically register itself.  
## Usage

Once the routes have been added to your application you can retrieve a list of template names from the api.

```bash
curl --GET {url}/api/template-api/users/1
```

Because this is based on Laravel Nova the `resource` needs to be the same as generated by the Resource or what is defined in `public static function uriKey()`.
`resourceId` is the ID of the model.

Which can return a result as followed:
```json
{
  "templates": [
    "user-1",
    "user",
    "resource",
    "model",
    "index"
  ],
  "resource": "users",
  "resourceId": "1"
}
```

Currently the packages constructs the template list as follows:
* {resource}-{modelKey}
* {resource}
* {model}-{modelKey}
* {model}
* resource
* model
* index

**Note that resource is only present when the resource name differs from the model name.**

### VueJs

Say you want to use this within Vue, here is an example of how you could implement this:
```js
const files = require.context('./templates/', true, /\.vue$/i);
files.keys().map(key => Vue.component('template-'+key.split('/').pop().split('.')[0], files(key).default));
```
From `app.js` I am loading all files within the `templates` folder and prefixing `template` as the name when registring them with Vue.
* `templates/index.vue` will be registered as `template-index`
* `templates/user.vue` will be registered as `template-user`
* `templates/user-1.vue` will be registered as `template-user-1`

Create a Vue file that will be rendered on specific routes.
In the example I am using `vue-router` and `beforeRouteEnter` to retrieve the correct template based on the current url.

```vue
<template>
    <component :is="`template-${template}`"></component>
</template>

<script>
    export default {
        beforeRouteEnter(to, from, next) {
            return axios.get(`/template-api${path}`)
                .then(({data: response}) => {
                    this.route = response
                })
                .then(next)
                .catch(error => {
                    // Show an error or redirect to an error page, dealer's choice
                })
        },

        data: () => ({
            template: null,
            route: null,
        }),

        created() {
            _.forEach(this.route.templates, template => {
                if (Vue.options.components[`template-${template}`] !== undefined) {
                    this.template = template
                    return false
                }
            })
        },
    }
</script>
```

### Disable resource templating

So everything is up an running, using a wildcard for routing, but you don't want people to access certain models.
You can disable (enabled by default) templating for certain resources by implementing the following interface: `Rjvandoesburg\NovaTemplating\Contracts\Templatable`.
This will add the method `isTemplatable(Request $request)` where you can add your own logic to decide if the given resource is templatable.

### Dealing with failures

When a resource cannot be found or an exception is thrown a json response is returned (with the error status code) containing error template names with a fallback on index.
```json
{
  "templates": [
    "404",
    "500",
    "index"
  ]
}
```

Within the Promise you can still access the response from a `catch` so it is up to you to decide how to handle the error.

## TODO

* Allow users to extend the template builder to alter the output of the API (e.g. they want to use slugs or use the name of a categorie for a template)
* Better/more/dynamic error templating
* Add error message to not found route

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [Robert-John van Doesburg](https://github.com/rjvandoesburg)
- [All Contributors](../../contributors)

Special thanks for Spatie for their guidelines and their packages as an inspiration
- [Spatie](https://spatie.be)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.