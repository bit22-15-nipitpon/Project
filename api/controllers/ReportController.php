<?php
require_once "models/Report.php";

class ReportController {
    private $table;

    public function __construct($db) {
        $this->table = new Report($db);
    }

    public function true($data, $code = 200) {
        http_response_code($code);
        echo json_encode([
            "success" => true,
            "data" => $data
        ]);
        exit;
    }

    public function false($message = "Not Found", $code = 404) {
        http_response_code($code);
        echo json_encode([
            "success" => false,
            "message" => $message
        ]);
        exit;
    }

    public function index() {
        $result = $this->table->getAll();

        if (!$result) {
            $this->false();
        }

        $this->true($result);
    }

    public function type() {
        $result = $this->table->getAllType();

        if (!$result) {
            $this->false();
        }

        $this->true($result);
    }

    public function progress($id) {
        $result = $this->table->inProgress($id);

        if (isset($result['inProgress'])) {
            http_response_code(404);
            echo json_encode([
                "success" => false,
                "message" => "This report isn't pending"
            ]);
            return;
        }

        $this->true($result);
    }

    public function resolved($id) {
        $result = $this->table->resolved($id);

        if (!$result) {
            http_response_code(404);
            echo json_encode([
                "success" => false,
                "message" => "This report isn't progress"
            ]);
            return;
        }

        $this->true($result);
    }

    public function create() {
        header("Content-Type: application/json; charset=UTF-8");
        try {
            // 1. รับข้อมูลจาก FormData
            $type_id  = $_POST['type_id'] ?? null;
            $title    = $_POST['title'] ?? null;
            $location = $_POST['location'] ?? null;
            $detail   = $_POST['detail'] ?? null;


            // 2. ตรวจสอบข้อมูล
            if (
                empty($type_id) ||
                empty($title) ||
                empty($location) ||
                empty($detail)
            ) {
                http_response_code(400);
                echo json_encode([
                    "success" => false,
                    "message" => "กรุณากรอกข้อมูลให้ครบ"
                ], JSON_UNESCAPED_UNICODE);
                return;
            }

            // 3. รับ user_id
            $user = $_REQUEST['user'] ?? null;
            $user_id = $user['user_id'];

            if (!$user_id) {
                http_response_code(401);

                echo json_encode([
                    "success" => false,
                    "message" => "Unauthorized"
                ], JSON_UNESCAPED_UNICODE);

                return;
            }

            // 4. จัดการรูป
            $image = null;
            if (isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
                if ($_FILES['image']['error'] !== UPLOAD_ERR_OK) {
                    throw new Exception("Upload image failed");
                }

                // จำกัดขนาด 5 MB
                if ($_FILES['image']['size'] > 5 * 1024 * 1024) {
                    throw new Exception("รูปภาพต้องมีขนาดไม่เกิน 5 MB");
                }

                // ตรวจ MIME type
                $allowedTypes = [
                    'image/jpeg' => 'jpg',
                    'image/png'  => 'png',
                    'image/gif'  => 'gif',
                    'image/webp' => 'webp'
                ];

                $finfo = new finfo(FILEINFO_MIME_TYPE);

                $mime = $finfo->file(
                    $_FILES['image']['tmp_name']
                );

                if (!isset($allowedTypes[$mime])) {
                    throw new Exception(
                        "รองรับเฉพาะ JPG, PNG, GIF และ WEBP"
                    );
                }

                // สร้างชื่อไฟล์
                $extension = $allowedTypes[$mime];
                $image = uniqid('report_', true) . '.' . $extension;

                // folder สำหรับเก็บรูป
                $uploadDir ='D:/BIT32/xampp/htdocs/project/frontend/asset/image/';

                // สร้าง folder ถ้ายังไม่มี
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }

                // Path เต็มของไฟล์
                $uploadPath = $uploadDir . $image;

                // ย้ายไฟล์
                if (
                    !move_uploaded_file(
                        $_FILES['image']['tmp_name'],
                        $uploadPath
                    )
                ) {
                    throw new Exception("ไม่สามารถบันทึกรูปภาพได้");
                }
            }

            // 5. เตรียม Data
            $data = [
                'user_id'  => intval($user_id),
                'type_id'  => intval($type_id),
                'image'    => $image,
                'title'    => trim($title),
                'location' => trim($location),
                'detail'   => trim($detail)
            ];

            // 6. Create Report
            $report_id = $this->table->create($data);

            // 7. Response
            http_response_code(201);
            echo json_encode([
                "success" => true,
                "message" => "สร้างรายงานสำเร็จ",
                "data" => [
                    "report_id" => $report_id['report_id']
                ]
            ], JSON_UNESCAPED_UNICODE);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                "success" => false,
                "message" => $e->getMessage()
            ], JSON_UNESCAPED_UNICODE);
        }
    }
}