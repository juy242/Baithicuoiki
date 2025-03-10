<div class="row">
    <div class="col-12">
        <center><img class="loading-cart" src="webroot/image/loader.gif" alt=""></center>
    </div>
</div>
<?php 
if (isset($_POST['addtocart'])) {
    $idproduct = $_POST['idproduct'];
    $soluong = $_POST['soluong'];
    $size = $_POST['size'];
    $mau = $_POST['mau'];
    $dongia = $_POST['dongia'];
    $soluongkho = check_product_soluong($idproduct, $size, $mau);

    if ($soluongkho >= $soluong) {
        $new_cart = array(array('MaSP' => $idproduct, 'SoLuong' => $soluong, 'Size' => $size, 'Mau' => $mau, 'DonGia' => $dongia));
        
        if (isset($_SESSION['cart_product'])) {
            $found = false; // check cart
            $alert = '';
            $cart = array(); // Khởi tạo biến $cart

            foreach ($_SESSION['cart_product'] as $item_cart) {
                if (($item_cart['MaSP'] === $idproduct) and ($item_cart['Size'] === $size) and ($item_cart['Mau'] === $mau)) {
                    if (($item_cart['SoLuong'] + $soluong) > 5) {
                        $found = true;
                        $cart[] = array('MaSP' => $item_cart['MaSP'], 'SoLuong' => $item_cart['SoLuong'], 'Size' => $item_cart['Size'], 'Mau' => $item_cart['Mau'], 'DonGia' => $item_cart['DonGia']);
                        $alert = 'vượt quá số lượng cho phép';
                    } else {
                        if (($item_cart['SoLuong'] + $soluong) <= $soluongkho) {
                            $cart[] = array('MaSP' => $item_cart['MaSP'], 'SoLuong' => ($item_cart['SoLuong'] + $soluong), 'Size' => $item_cart['Size'], 'Mau' => $item_cart['Mau'], 'DonGia' => $item_cart['DonGia']);
                            $found = true;
                            $alert = 'Đã thêm vào giỏ hàng';
                        } else {
                            $cart[] = array('MaSP' => $item_cart['MaSP'], 'SoLuong' => $item_cart['SoLuong'], 'Size' => $item_cart['Size'], 'Mau' => $item_cart['Mau'], 'DonGia' => $item_cart['DonGia']);
                            $found = true;
                            $alert = 'Xin lỗi !!! đã hết hàng';
                        }
                    }
                } else {
                    $cart[] = array('MaSP' => $item_cart['MaSP'], 'SoLuong' => $item_cart['SoLuong'], 'Size' => $item_cart['Size'], 'Mau' => $item_cart['Mau'], 'DonGia' => $item_cart['DonGia']);
                }
            }

            if ($found == false) {
                $_SESSION['cart_product'] = array_merge($cart, $new_cart);
            } else {
                $_SESSION["cart_product"] = $cart;
            }

        } else {
            $_SESSION['cart_product'] = $new_cart;
            $alert = 'Đã thêm vào giỏ hàng';
        }

        if ($alert === 'Xin lỗi !!! đã hết hàng' || $alert === 'Đã hết hàng') {
            header('location:?view=product-detail&id=' . $idproduct . '&alert=' . urlencode($alert));
        } else if ($alert === 'Đã thêm vào giỏ hàng') { // Sửa lỗi so sánh
            header('location:?view=cart&alert=' . urlencode($alert) . '&id=' . $idproduct);
        }

        exit(); // Make sure to exit after sending the header

    } else {
        $alert = 'Đã hết hàng';
        header('location:?view=product-detail&id=' . $idproduct . '&alert=' . urlencode($alert));
        exit();
    }
}

if(isset($_POST['delete_cart_product'])){
    $masp=$_POST['productID'];
    $size=$_POST['size'];
    $mau=$_POST['mau'];
    $cart = array(); // Khởi tạo biến $cart
    foreach($_SESSION['cart_product'] as $item_cart){
        if($item_cart['MaSP']==$masp && $item_cart['Size']==$size and ($item_cart['Mau']===$mau)){}
        else{
            $cart[]=array('MaSP'=>$item_cart['MaSP'],'TenSP'=>$item_cart['TenSP'],'SoLuong'=>$item_cart['SoLuong'],'Size'=>$item_cart['Size'],'Mau'=>$item_cart['Mau'],'DonGia'=>$item_cart['DonGia']);            
        }
    }
    $_SESSION['cart_product']=$cart;
    $alert='Đã xóa thành công';
    header('location:?view=cart&alert='.$alert);
}

//xoa toan bo giỏ hàng
// if(isset($_GET['xoaall'])){
//  unset($_SESSION['cart_product']);
//  header('location:./../../index.php?view=cart');
// }

require 'webroot/PHPMailer/src/Exception.php';
require 'webroot/PHPMailer/src/PHPMailer.php';
require 'webroot/PHPMailer/src/SMTP.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
if(isset($_POST['order'])){
    $nn=$_POST['fname'];//người nhận hàng
    $dcnn=$_POST['address']; //địa chỉ người nhận
    $sdtnn=$_POST['phone'];//số điện thoại người nhận
    $kh=$_SESSION['laclac_khachang'];
    $makh=$kh['MaKH'];
    $tt=$_POST['tongtien'];
    $talbe='';
    foreach($_SESSION['cart_product'] as $item_cart) { $product=mysqli_fetch_array(product($item_cart['MaSP'])); 
        $number = str_replace(',', '', $item_cart['DonGia']);  $dongia=number_format($number*$item_cart['SoLuong']);
        $talbe=$talbe. '
        <tr>
        <td>'.$product['TenSP'].'</td>
        <td>'.$item_cart['SoLuong'].'</td> 
        <td>'.$dongia.'</td>
        </tr>';
    } 
    $message = '
    <html>
    <head>
    <title>Đơn hàng của bạn</title>
    <style>
        table {
            font-family: Arial, sans-serif;
            font-size: 14px;
            background-color: #f7f7f7;
            width: 100%;
        }
        th {
            background-color: #333;
            color: #fff;
            font-weight: bold;
            padding: 10px;
        }
        table, th, td {
            border: 1px solid black;
            border-collapse: collapse;
        }
        th, td {
            text-align: center;
            padding: 10px;
        }
    </style> 
    </head>
    <body>
    <p>Kính gửi '.$kh['TenKH'].',</p>
    <p>Cảm ơn bạn đã đặt hàng tại Lac Lac Shoes. Dưới đây là chi tiết đơn hàng của bạn:</p>
    <table>
    <tr>
    <th>Sản phẩm</th>
    <th>Số lượng</th>
    <th>Giá</th>
    </tr>'.$talbe.'
    </table>
    <p>Tổng cộng: '. number_format($tt).' đ</p>
    <p>Thông tin người nhận hàng:</p>
    <p><strong>Người nhận hàng:</strong> '.$nn.'</p>
    <p><strong>Địa chỉ nhận hàng:</strong> '.$dcnn.'</p>
    <p><strong>Số điện thoại liên hệ:</strong> '.$sdtnn.'</p>
    <p>Chúng tôi sẽ liên hệ với bạn sớm nhất có thể để xác nhận đơn hàng và thông báo thêm về quá trình vận chuyển. Xin vui lòng kiểm tra email của bạn thường xuyên.</p>
    <p>Xin chân thành cảm ơn!</p>
    </body>
    </html>';

        $order=order_product($nn,$dcnn,$sdtnn,$makh,$tt);
        if($order){
            $mail = new PHPMailer(true);
            $mail->isSMTP();                                            // sử dụng SMTP
            $mail->Host       = 'smtp.gmail.com';                       // SMTP server
            $mail->SMTPAuth   = true;                                   // bật chế độ xác thực SMTP
            $mail->Username   = 'laclacshoes@gmail.com';        // tài khoản đăng nhập SMTP
            $mail->Password   = 'vuhycycmugcawimu';                         // mật khẩu đăng nhập SMTP
            $mail->SMTPSecure = 'tls';                                  // giao thức bảo mật TLS
            $mail->Port       = 587;
            $mail->setFrom('laclacshoes@gmail.com', 'Lac Lac Shoes');          // địa chỉ email và tên người gửi
            $mail->addAddress($kh['Email'], $kh['TenKH']); // địa chỉ email và tên người nhận
            $mail->Subject = ' Lac Lac Shoes - DON HANG CUA BAN';                               // tiêu đề email
            $mail->Body    = $message;     
            $mail->isHTML(true);                            // định dạng email dưới dạng HTML
            // $mail->addAttachment('path/to/file.pdf');       // đính kèm tập tin PDF
            if ($mail->send()) {
                header('location:?view=order-complete');
            } else {
                echo 'Email could not be sent';
                echo 'Mailer Error: ' . $mail->ErrorInfo;
            } 
        }
}
?>