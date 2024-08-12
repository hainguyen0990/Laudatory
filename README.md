## Hướng dẫn cài đặt trên localhost

- Virtual host: http://laudatory.my/
- CSDL tên laudatory. Import từ file laudatory.sql  từ thư mục intern
- Chép file config.php trong thư mục intern ra đúng vị trí vào file src/
- Chép file config_global.php trong thư mục intern ra đúng vị trí vào file src/data/config/
- Nếu đặt tên CSDL khác thì cần sửa trong file config.php 
- Đăng nhập quản trị bằng tài khoản `admin / Ha0904462434`
- Chú ý toàn bộ các tài khoản nếu biết mật khẩu phải reset ở quản trị
- Chú ý: Nếu đặt tên virtual host khác thì cần sửa lại trong config_global.php dòng $global_config['my_domains'] theo tên virtual host

### Demo các chức năng tại đây: 

### Công việc của em Hải:
```
*** Chức năng trong admin
1.Trang chủ 
+ Duyệt nhanh các đề xuất khen thưởng mới và xem biểu đồ thống kê
2. Quản lý phòng ban
+ Thêm phòng ban 
+ Thêm chức vụ 
+ Thêm nhân viên vào phòng ban
+ Quản lý nhân viên
3. Thêm danh mục khen thưởng 
+ Chọn phòng ban để đề xuất khen thưởng 
+ Trưởng phòng hoặc ban giám đốc đề xuất khen thưởng
+ Ban giám đốc duyệt hoặc từ chốt đề xuất khen thưởng
```
```
*** Chức năng ngoài site 
1.Trang chủ 
+ Xem biểu đồ thống kê và Top 10 nhân viên được khen thưởng nhiều
2. Xem danh sách các phòng ban 
+ Chọn phòng ban và lựa chọn tiếp vào danh mục tùy thích để xem những nhân viên được duyệt đề xuất khen thưởng
3. Tổng kết khen thưởng theo năm 
+ Lựa chọn năm mà bạn muốn xem các đề xuất khen thưởng của năm đó 
+ Chọn các danh mục tùy thích đề xem được những nhân viên được khen thưởng
```
