# Module DocPDF for NukeViet 4.x

Module hỗ trợ chuyển đổi file (PDF <-> Word) và xử lý file PDF (Nối/Cắt).

## Yêu cầu
- NukeViet 4.5.07 trở lên.
- PHP >= 7.4.
- Composer.

## Cài đặt

1. Copy thư mục `doc-pdf` vào `modules/`.
2. Mở terminal tại thư mục `modules/doc-pdf/` và chạy lệnh sau để cài đặt các thư viện:
   ```bash
   composer install
   ```
   *Lưu ý: Nếu không chạy lệnh này, module sẽ báo lỗi thiếu thư viện.*

3. Vào AdminCP -> Quản lý module -> Cài đặt module `DocPDF`.

## Cấu hình Google Drive API (Cho chức năng Convert)

Chức năng chuyển đổi (PDF <-> Word) sử dụng Google Drive API để xử lý (Upload file lên Drive -> Convert -> Download về). Bạn cần cấu hình Service Account:

1. Truy cập [Google Cloud Console](https://console.cloud.google.com/).
2. Tạo Project mới.
3. Kích hoạt **Google Drive API** cho project này.
4. Vào **Credentials** -> **Create Credentials** -> **Service Account**.
5. Tạo Key cho Service Account dưới dạng **JSON**.
6. Tải file JSON về máy.
7. Mở file JSON bằng trình soạn thảo text, copy toàn bộ nội dung.
8. Vào AdminCP NukeViet -> Module DocPDF -> Cấu hình -> Dán nội dung JSON vào ô **Google Service Account JSON**.

## Cronjob (Dọn dẹp file tạm)

Các file upload và file kết quả được lưu trong `uploads/doc-pdf/tmp`. Bạn cần cấu hình Cronjob để xóa các file cũ:

- Module đã tích hợp sẵn chức năng dọn dẹp khi admin truy cập log.
- Để tự động hóa, hãy thêm Cron vào hệ thống NukeViet (nếu module hỗ trợ func cron) hoặc dùng Cron hệ thống server để xóa file trong `uploads/doc-pdf/tmp` cũ hơn 60 phút.
