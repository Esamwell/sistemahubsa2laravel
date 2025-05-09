<?php

return [

    'filename' => '_ide_helper.php',

    'meta_filename' => '.phpstorm.meta.php',

    'include_fluent' => false,

    'include_factory_builders' => false,

    'write_model_magic_where' => true,

    'write_model_external_builder_methods' => true,

    'write_model_relation_count_properties' => true,

    'write_eloquent_model_mixins' => false,

    'include_helpers' => false,

    'helper_files' => [
        base_path().'/vendor/laravel/framework/src/Illuminate/Support/helpers.php',
    ],

    'model_locations' => [
        'app',
    ],

    'ignored_models' => [

    ],

    'model_hooks' => [
        // App\Support\Models\Hooks\MyHook::class
    ],

    'extra' => [
        'Eloquent' => ['Illuminate\Database\Eloquent\Builder', 'Illuminate\Database\Query\Builder'],
        'Session' => ['Illuminate\Session\Store'],
    ],

    'magic' => [],

    'interfaces' => [

    ],

    'custom_db_types' => [

    ],

    'model_camel_case_properties' => false,

    'type_overrides' => [
        'integer' => 'int',
        'boolean' => 'bool',
    ],

    'include_class_docblocks' => false,

    'force_fqn' => false,

    'additional_relation_types' => [],

    'post_migrate' => [
        // 'ide-helper:models --nowrite',
    ],

]; 