## Project Setup:

### 1. Clone the repo to htdocs or www folder

```
git clone gslgit@192.168.10.63:/home/gslgit/ccKnowledgeBase.git
```

### 2. go to the directory 
```
cd ccKnowledgeBase/kbs2.1
```

### 3. Install Composer
```
composer install
```

### 4. Create .env File
```
copy .env.example .env
```

### 5. Generate Key
```
php artisan key:generate
```

### 6. Enable Permission (for Linux User)

```
sudo chmod -R 777 storage
```

## 7. Database Section

```
Create db kbs
```
```
php artisan migrate
```
```
php artisan db:seed --class=GroupSeeder
php artisan db:seed --class=PermissionTableSeeder
php artisan db:seed --class=RoleTableSeeder
php artisan db:seed --class=UserTableSeeder
php artisan db:seed --class=BulkUserTableSeeder
php artisan db:seed --class=CategoryTableSeeder
```
###or
```
php artisan migrate:fresh
php artisan db:seed --class=AllSeeder
```

## 8. Authentication

```
php artisan passport:install
```

# Great ! Done! 

## 9. From Browser
```html
http://localhost/ccKnowledgeBase/public
```

### 10. From Postman ### post request
```html
http://localhost/ccKnowledgeBase/public/api/login

body: 
    username: admin
    password: 123456
```


