
# Tên Đề Tài : Book Tour Travel Hotel

# Danh Sách nhóm 5
````````
Serial No.	Student Name	             Enrollment Number
1	        Lê Thanh Thiên               Student1420696
2	        Nguyễn Ngọc Thanh Chương     Student1416070
3	        Quảng Đại Vi	             Student1420703    
````````


# Cách Cài Đặt

### Clone dự án về

Copy file tạo file .env > copy nội dung file .evn.example qua

### Chạy các lệnh sau theo thứ tự:

```
composer install
npm install
npm i vue-loading-overlay
php artisan key:generate
composer require laravel/passport --ignore-platform-req=ext-sodium
```


### Chạy lệnh Tạo database:
 
```
php artisan migrate
``` 

### tạo fake dữ liệu
```
php artisan db:seed
php artisan passport:install
```

### Mở 2 cửa số Terminal  và chạy tiếp 2 lệnh sau: 

```
npm run dev
php artisan serve
```
php artisan passport:install--force