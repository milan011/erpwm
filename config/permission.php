<?php

return [

    'models' => [

        /*
         * 当使用这个包中的 “HasRoles” 特性时，我们需要知道应该
         * 使用哪个 Eloquent 模型来获取您的权限。
         * 当然，它通常只是“权限（Permission）”模型，你也可以使用任何你喜欢的模型。
         * 
         * 您使用的权限模型必须实现
         *  `Spatie\Permission\Contracts\Permission` 契约。
         */

        'permission' => Spatie\Permission\Models\Permission::class,

         /*
         * 当使用这个包中的 “HasRoles” 特性时，
         * 我们需要知道应该使用哪个 Eloquent 模型来检索你的角色。
         * 当然，它通常只是 “角色（Role）” 模型，你也可以使用任何你喜欢的模型。
         *
         * 您使用的权限模型必须实现
         * `Spatie\Permission\Contracts\Role` 契约。
         */

        'role' => Spatie\Permission\Models\Role::class,

    ],

    'table_names' => [

        /*
         * 当使用这个包中的 “HasRoles” 特性时，
         * 我们需要知道哪个表应该用来检索你的“角色”。 
         * 我们选择了一个基本的默认值，但您可以轻松将其更改为您喜欢的。
         */

        'roles' => 'roles',

        /*
         * 当使用这个包中的 “HasRoles” 特性时，
         * 我们需要知道哪个表应该用来检索你的权限。 
         * 我们选择了一个基本的默认值，但您可以轻松将其更改为您喜欢的任何表。
         */

        'permissions' => 'permissions',

        /*
         * 
         * 当使用这个包中的 “HasRoles” 特征时，
         * 我们需要知道应该使用哪个表来检索你的“模型权限”。 
         * 我们选择了一个基本的默认值，但您可以轻松将其更改为您喜欢的任何表。
         * 
         */

        'model_has_permissions' => 'model_has_permissions',

        /*
         * 当使用这个包中的 “HasRoles” 特性时，
         * 我们需要知道哪个表应该用来检索你的“模型角色”。 
         * 我们选择了一个基本的默认值，但您可以轻松将其更改为您喜欢的任何表。
         */

        'model_has_roles' => 'model_has_roles',

        /*
         * 当使用这个包中的 “HasRoles” 特性时，
         * 我们需要知道应该使用哪个表来检索您的“角色权限”。 
         * 我们选择了一个基本的默认值，但您可以轻松将其更改为您喜欢的任何表。
         */

        'role_has_permissions' => 'role_has_permissions',
    ],

    /*
     * 默认情况下，所有权限将被缓存24小时，
     * 除非更新许可或者更新角色来立即刷新缓存。
     */

    'cache_expiration_time' => 60 * 24 * 7,

    /*
     * 设置为 true 时，所需的权限/角色名称（ permission/role）将添加到异常消息中。
     * 在某些情况下，这可能被认为是信息泄漏，
     * 所以为了获得最佳安全性，默认设置为 false。
     */

    'display_permission_in_exception' => false,
];
