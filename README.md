# Simple permission
Simple permission management for PHP.
## Getting Started
These instructions will get you quick start for simple permission management in your PHP project.
### Installation
* Require this package in the `composer.json`  
`composer require ykozhemiaka/simple-permission`  
* Before using all commands which provide this package you should init `Capsule` (for example in `index.php` where boots application)  
```php
use Illuminate\Database\Capsule\Manager as Capsule;

$capsule = new Capsule;

$capsule->addConnection([
            'driver' => 'mysql',
            'host' => 'localhost',
            'database' => 'simple_permissions',
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8mb4'
        ]);

$capsule->setAsGlobal();
 
$capsule->bootEloquent();
```
* After required the package you should create all necessary tables.  
If you've already created `users` table you couldn't do next instruction, but if you haven't `users` table in your database you should execute next script to create the table.  
```php
use SimplePermission\Database\Schemas\UsersSchema;

$userSchema = new UsersSchema($capsule);
$userSchema->createTable();
```
* Next, you need to create several tables that are associated with permission tables.
```php
use SimplePermission\Database\Schemas\PermissionSchemas;

$permissionSchemas = new PermissionSchemas($capsule);
$permissionSchemas->createTable();
```
* If table users name isn't `users` you should set custom name in `SimplePermission\Models\User.php`
```php
namespace SimplePermission\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasPermission;

    protected $table = 'custom-table-name';

...
```
### Quick start
#### Management permissions
* Create new permission "Edit article".
```php
use SimplePermission\Repositories\PermissionRepository;

$permissionRepo = new PermissionRepository();
$permission = $permissionRepo->create('Edit article');
```
* Update created permission above.
```php
$id = 1;

$permission = $permissionRepo->update('Edit posts', 1)
```
* Delete permission by name
```php
$permissionRepo->deleteByName('Edit article');
```
Or by id
```php
$permissionRepo->deleteById(2)
```
* Assign role to permission
```php
$role = Role::find(1);
//or you can use role id $role = 1;

$permission = $permissionRepo->whereId(1)->assignRole($role);
```
* Revoke permission to role
```php
$permissionRepo->whereId(1)->terminateToRole($role);
```
#### Management permissions via user
* Give permission to user.
```php
$user = User::find(1);

$role = Role::find(1);
//or you can use role id $role = 1;
$permission = Permission::find(1);
//or you can use permission id $permission = 1;

$user->allowTo($permission);
```
* Assign role to user.
```php
$user->grantRole($role);
```
* Verify permission.
```php
$user->ableTo('Edit posts');
```
* Verify role.
```php
$user->hasRole('Admin');
```
* Revoke permission.
```php
$user->terminateToPermission($permission);
```
* Revoke role.
```php
$user->terminateToRole($role);
```
#### Management roles
* Create role.
```php
$roleRepo = new RoleRepository();
$role = $roleRepo->create('Admin');
```
* Fetch all permissions attached to role.
```php
$role->permissions;
```
* Update role.
```php
$role = $roleRepo->update('Moderator', $role->id);
```
* Delete by name
```php
$roleRepo->deleteByName($role->name);
```
Or delete by id
```php
$roleRepo->deleteById($role->id);
```
* Attach permission to role.
```php
$role = $roleRepo->whereId(1)->grant($permission)
```
* Assign role to user.
```php
$role = $roleRepo->whereId(1)->assignRoleTo($user)
```
* Revoke role to user.
```php
$roleTerminate = $roleRepo->whereId(1)->terminateToUser($user);
```
* Revoke permission to user.
```php
$role = $roleRepo->whereId(1)->terminateToPermission($permission);
```
## Built With
* [illuminate/database](https://packagist.org/packages/illuminate/database) - The Illuminate Database component is a full database toolkit for PHP.
## Authors
* **Yaroslav Kozhemiaka** - *Initial work* - [Yaroslav Kozhemiaka](https://github.com/YaroslavKozhemiaka)
