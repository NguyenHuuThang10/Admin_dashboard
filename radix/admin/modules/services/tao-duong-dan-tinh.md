Các bước tạo đường dẫn tĩnh 
- Bước 1: Lấy tên (Trường ảnh)
- Bước 2: Thay thế
+ Chuyển tất cả ký tự thành chữ thường
+ Chuyển các chữ tiếng việt có dấu thành không dấu.
+ Chuyển các ký tự đặc biệt (Bao gồm khoảng cách) => -
- Bước 3: Tự động điền vào input slug

**Ngôn ngữ lập trình sử dụng**

1. PHP:
- Tạo slug tự đọng dựa vào tên(Trường slug không nhập)
- Update slug vào CSDL

2. JS
- Tạo slug tự động dựa vào tên(Bắt sự kiện onkeyup vào trường tên)
- Điền dữ liệu vào trường slug 

=> Gõ ký tự ở trường tên => trường slug tự đọng có slug

á|à|ả|ạ|ã|ă|ắ|ằ|ẳ|ẵ|ặ|â|ấ|ầ|ẩ|ẫ|ậ => a
é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ => e
i|í|ì|ỉ|ĩ|ị => i
ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ => o
ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự => u
ý|ỳ|ỷ|ỹ|ỵ => y
đ => d

\`|\~|\!|\@|\#|\||\$|\%|\^|\&|\*|\(|\)|\+|\=|\,|\.|\/|\?|\>|\<|\'|\"|\:|\;|_ => xoá