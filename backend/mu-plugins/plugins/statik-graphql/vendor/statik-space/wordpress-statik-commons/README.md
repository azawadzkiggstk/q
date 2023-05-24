<p align="center">
  <img width="400" src="assets/images/statik.png?raw=true" align="center" alt="Statik logo">
</p>

<h1 align="center">
  Statik Commons
</h1>

The collection of useful classes and interfaces for use in Statik plugins. It contains all the base classes that are
used, e.g: to create a dashboard panel, manage REST API or generate form's setting fields.

The package can be loaded into the plugins via composer.

## ❓ What is in this document

- [💼 Main plugin features](#-main-plugin-features)
- [👨‍💻 How to use Statik Commons?](#-how-to-use-statik-commons)
- [🧰 Configuration module](#-configuration-module)
- [🎛 Settings generator](#-settings-generator)
- [🪚 Development mode](#-development-mode)
- [🎉 Release procedure](#-release-procedure)

## 💼 Main plugin features

* interface for manage WP CLI, allows creating new commands.
* interface for manage plugins Config, by default allows saving settings into the database and manages records.
* WP dashboard pages manager, for easier creating new dashboard pages,
* WP REST API manager, allows creating new API endpoints,
* settings generator, that main responsibility is creating fields and connect these with Config values.

## 👨‍💻 How to use Statik Commons?

Statik Commons can be loaded into the plugin via composer. To do that in the `repository` section should be added a new
record with the Statik Commons repository.

Note that Commons are not listed publicly and in the
[Composer documentation](https://getcomposer.org/doc/articles/authentication-for-private-packages.md) can be found more
details on how to authorize access to the private repository.

```json5
{
  "repositories": [
    {
      "type": "vcs",
      "url": "git@github.com:statik-space/wordpress-statik-commons.git"
    }
  ]
}
```

## 🧰 Configuration module

The configuration interface allows easy management of data saved by plugins. Data can be saved to various drivers, such
as a database. Thanks to the use of the [laravel/helpers](https://github.com/laravel/helpers) library, the data is saved
on several levels.

### How to use?

To create your own configuration representation, create a class that will extends `Statik\Common\Config\AbstractConfig`.
New configuration instances should be created using the static Instance method to prevent repetition.

```php
$namespace = 'statik_config';
$driver = new Statik\GraphQL\Common\Config\Driver\DatabaseDriver($namespace);
$config = Config::Instance($namespace, $driver);

$config->toArray();
$config->set('test.config.value', 'example_value');
$config->save();
```

## 🎛 Settings generator

The generator module is prepared to generate HTML fields. By the default module generate an HTML table with all fields
based on the inputted config.

### How to use?

To use Generator have to register fields and initialize the registry to make sure all values are loaded. Fields do not
have to be registered in the same place where they are rendered - this is fully separate process.

```php
$generator = new Statik\GraphQL\Common\Settings\Generator($config, 'generator_namespace');

$generator->registerFields( //Register new fields group
    'default_settings',
    [
        'config.default_field' => [
            'type'  => 'input',
            'label' => \__('Default field', 'statik'),
            'attrs' => [
                'class'    => 'regular-text',
                'required' => 'required'
            ]
        ]
    ]
);

$generator->initializeFields(); //Initialize fields values.
$generator->generateStructure('default_settings'); //And finally render group fields.
```

### Conditions

Each field can have defined two values for conditions: `conditions` and `conditionsCallback`. The first one allows hide
or show the input dynamically (through JavaScript) and require `key => value` structure with equal (or not when key ends
with `!`) value. The second one is executed on the PHP level and determines if the field will be generated.

### Available fields

All fields required to have a valid structure. The field is identified based on the array key and have to be unique.
Required field keys: `type`, `label`.

In attrs property can be set some additional attributes for a field HTML tag. Allowed key types: `class`, `type`,
`required`, `disabled`, `rows` (for textarea). For any other key will be added `data-` prefix.

### Input field

There is no any additional requirements for this field.

```php
$fields = [
    'inputField' => [
        'type'               => 'input',
        'label'              => \__('Input', 'statik'),
        'description'        => \__('This is a input field', 'statik'),
        'value'              => "This is input value and can't be changed",
        'conditions'         => ['otherInputField.value' => 'value1'],
        'conditionsCallback' => fn ($fieldInterface) => $fieldInterface->value === 'show',
        'attrs'              => [
            'disabled' => 'disabled', 
            'class' => 'regular-text', 
            'required' => 'required'
        ]
    ]
];
```

### Input type checkbox field

Additional required keys: `values`. The `values` key can be an array or callback. When it is a callback the function is
executed only when the field is rendering.

```php
$fields = [
    'inputCheckboxField' => [
        'type'        => 'input:checkbox',
        'label'       => \__('Input type checkbox', 'statik'),
        'description' => \__('This is a input type checkbox field', 'statik'),
        'default'     => ['option1' => 1],
        'values'      => fn () => ['option1' => 'Option 1', 'option2' => 'Option 2'],
    ]
];
```

### Heading field

This field displays a simple heading, without any editable options. Allows to group the fields.

```php
$fields = [
    'headingField' => [
        'type'        => 'heading',
        'label'       => \__('Heading', 'statik'),
        'description' => \__('This is a heading field', 'statik'),
    ],
];
```

### Break line field

This field displays simple break line, without any editable options.

```php
$fields = [
    'breakLineField' => [
        'type' => 'break'
    ],
];
```

### Textarea field

The configuration is exactly the same as in the Input field.

### WYSIWYG editor field

The configuration is exactly the same as in the Input field.

### Select field

Additional required keys: `values`. Select allow async option and load available options after page load. This is a good
option to retrieve data from the API and does not slow down the dashboard loading.

```php
$field = [
    'selectField' => [
        'type'        => 'select',
        'label'       => \__('Select', 'statik'),
        'description' => \__('This is a select field', 'statik'),
        'values'      => [ExampleApiClass::class, 'callApi'],
        'attrs'       => ['async' => true]
    ]
];
```

### Multiple select field.

Additional required keys: `values`. In attributes can be defined extra key `validation` with the rules for new records.

```php
$field = [
    'multipleSelectField' => [
        'type'        => 'select:multiple',
        'label'       => \__('Multiple select field', 'statik'),
        'description' => \__('This is a multiple select field', 'statik'),
        'values'      => ['option1' => 'Option 1', 'option2' => 'Option 2'],
        'attrs'       => ['class' => 'regular-text', 'validation' => '^[\w-]+$']
    ]
];
```

### Repeater field

Additional required keys: `fields`. Allow create a repeatable groups with other fields.

```php
$fields = [
    'repeaterField' => [
        'type'        => 'repeater',
        'label'       => \__('Repeater', 'statik'),
        'description' => __('This is a multiple select field', 'statik'),
        'fields'      => [] //Array of other fields.
    ]
];
```

## 🪚 Development mode

Development work on the plugin is only possible within a properly prepared package. Production version of the plugin
cannot be launched in full development mode due to lack of many required files.

The development mode in the plugin can be activated by the constant `STATIK_COMMON_DEVELOPMENT`. When the value of the
constant is set to true, it is loaded on the dashboard and not the minified asset version.

Download development dependencies The development plugin requires the installation of external dependencies, such as
Composer or NPM dependencies.

To start working, run the following commands:

```bash
composer install
npm install
```

### Gulp tasks

Actions, like compiling the SASS files or build JS files, are managed by Gulp tasks. All available tasks can be found
in `gulpfile.js`.

## 🎉 Release procedure

The process of publishing a new version of the plugins should follow the steps below. During this process a significant
part of the work is done by Github Actions. After releasing a new version of the plugin catalog is zipped and sent to
Statik bucket on AWS infrastructure. Thanks to this plugin can be downloaded directly to any of our WordPress instances.

> :warning: Make sure that all changes are developing on feature branches. During the release procedure, all changes
> from the selected branch will be included in the release.

### Procedure for releasing a new version of the plugin

1. All new changes that should be included in the new release should be pushed to the selected branch.
2. Run the Github Action script named `Create package release`. Action has to be run manually on a selected branch with
   an extra parameter contain the new version of the plugin.
3. When the script finish in the Release tab should be visible a new one.
4. That's all, the new version of the plugin has been released 🎉

## 📝 Licence

The Statik Commons is licensed under the GPLv3 or later.