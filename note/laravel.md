- Cách chạy laravel
composer global require laravel/installer
laravel new example-app
đợi đến khi chọn SQL
cd đến foder vừa tạo
php artisan serve

- Migration trong laravel - dùng để tạo bảng(table)
+ Thực hiện migration bằng lệnh: php artisan migrate
+ Để rollback lại lệnh vừa thực thi: php artisan migrate:rollback
+ Tạo 1 migration cho 1 báng nới: php artisan make:migration create_ten_bang_moi
+ Để rollback lại nhiều bước: php artisan migrate:rollback --step=số bước
+ Rollback lại toàn bộ database và tạo lại từ đầu (reset database): php artisan migrate:refresh

- Seeder - dùng để thêm dữ liệu vào bảng
+ Để tạo file seeder: php artisan make:seeder TenBangSeeder 
+ Để chạy file seeder:
Cách 1: php artisan db:seed --class=TenBangSeeder
Cách 2: Thêm seeder vào file DatabaseSeeder để chạy

- Factory: Tạo dữ liệu mẫu số lượng lớn (php artisan make:model TenBang )
+ Để tạo file factory: php artisan make:factory TenBangFactory
+ Thêm Factory vào file DatabaseSeeder để tạo dữ liệu mẫu

- Để tạo migration/seeder/factory nhanh nhất
+ Tạo model trước bằng lệnh php artisan make:model TenBang --all
+ Khai báo các trường dữ liệu trong file migration, sau đó chạy lệnh php artisan migrate
+ Khai báo dữ liệu mẫu trong seeder hoặc factory, sau đó gọi vào trong file DatabaseSeeder 
-> Chạy lệnh php artisan db:seed để tạo dữ liệu mẫu

Tạo controller: php artisan make:controller TenBangController --resource



