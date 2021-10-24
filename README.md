
# Installation instructions.


1- install the package using the following snippet at your composer.json

```php
{ 
   "name": "mohamedreda/authorizable",
   "type": "vcs",
   "url" : "https://github.com/M07amedReda/authorizable.git"
},
```
2- run the following command
```php artisan vendor:publish --tag="authorizable" ```
> this will publish the seeder and the migration file to get you started.


3- extend the user model at your user model.

```php
use MohamedReda\Authorizable\Models\User as Authorizable;
class User extends Authorizable
{
}
```
or you can directly import the trait and use it at your own user model.
```php
use MohamedReda\Authorizable\Traits\Authorizable;
```
> don't forget to typecast the permissions to array.
```php
protected $casts = ['permissions' => 'array'];
```

The authorizable trait is there to handle the authorization process for artify commands , but feel free to use it for your own purposes.
It contains of a few methods that"s really helpful for you to handle authorization anywhere within your application.
Let"s dive into the Methods there.
> The Trait supports checking roles within your roles table and the users table ( secondary permissions to be assigned for users )  

#### Authorization Methods. 

##### 1.0 hasRole Method
  <p>This method accepts only one argument and it should be string, the string should match a role name within your permissions column either on users table or roles table.</p>
  
  ```bash
  
  $user = \App\User::first();
  
  dd($user->hasRole("create-post")); // if the current logged in user has the ability to "create-post", it will return true
  
  ```
  
  
  The method is going to search for the permission associated with the user either on roles table or users table.
##### 2.0 hasAnyRole Method
  <p>I think you"ve guessed what"s going on here, It searches for all of the roles you pass and returns true on the first role that a user may have</p>
  
  ```bash
    dd($user->hasAnyRole(["create-post","foo-bar","approve-post"])); // returns true/false;
  ```
##### 3.0 hasAllRole Method
 <p>This method accepts one argument, it should be an array containing of the permissions you are checking for. </p>
 This method checks on all the roles passed, whether the user has them all or not.
 
 ```bash 
   dd($user->hasAllRole(["foo-bar","upgrade-user"])); // returns true/false;
 ```
 
##### 3.0 inRole Method
 <p>This method checks if the user"s rank is typically equal to the passed argument. </p>
 - Slug that represents your permissions in role table.
 - returns boolean 

  
```bash
 dd($user->inRole("admin"); // This slug exists within the seeder
```

<hr>

#### Permissions Handling Methods.
<b>The permissions are assigned to the current user you are working on only. i.e you can retrieve the user by finding him/her or get the authenticated user then handle his/her permisisons.</b>
##### 1.0 Add Permission
   
   <p>This method accepts two arguments which  are the permissions and boolean value.</p>
   - Permissions can be either string or array.
   - boolean value represents the ability of the user to do mentioned permission.
   - returns boolean.


  ```bash
     $user->addPermission("create-custom-post"); // second parameter is set to true by default, so the added permission is available for this user.
     $user->addPermission("create-something",false) // the user isn\'t allowed to "create-something" now.
     $user->addPermission(["create-something" => true , "can-push-code" => false]) 
     /* you don\'t need the second parameter now as the key and value of this array is going to be in charge of handling the permissions. */
     
  ```
  
  <hr>

##### 2.0 Update Permission
 
<p> This method accepts three arguments</p>
 - Permission , should be a string.
 - boolean value represents the ability of the user to do mentioned permission.
 - boolean value , represents creating the permission if it doesn't exist.
 - returns boolean.


 ```bash
  $user->updatePermission("create-post"); // this will update the permission to set it to true.
  $user->updatePermission("create-post",false); // this will update the permission to set it to false
  $user->updatePermission("foo-bar",false,true); // this will create the permission if it doesn\'t exist and set it to false.
 ```
 
 <hr>

##### 3.0 Remove Permission
 <p>This method accepts one argument only representing the permission</p>
 - n of permissions can be passed a separate parameter.
 

 - returns boolean


 <p>If the permission isn't set, an exception is thrown.</p>

 ```bash
   $user->removePermission("create-post"); // returns boolean
   $user->removePermission("create-post","delete-post","whatever-role",...); // returns boolean
 ```
