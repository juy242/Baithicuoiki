<?php
include_once('../../model/database.php');
include('../../config/config.php');

if(isset($_POST['xlthem'])){
    $tensp = $_POST['tensp'];
    $madm = $_POST['madm'];
    $mancc = $_POST['mancc'];
    $dongia = $_POST['dongia'];
    $mota = $_POST['mota'];

    // Thêm sản phẩm
    $sql_them = "INSERT INTO `sanpham`(`TenSP`, `MaDM`, `MaNCC`, `DonGia`, `MoTa`) VALUES ('$tensp', $madm, $mancc, $dongia, '$mota')";
    mysqli_query($conn, $sql_them);
    $masp = mysqli_insert_id($conn);

    // Thêm size sản phẩm vào bảng chitietsanpham
    if(isset($_POST['size'])){
        foreach($_POST['size'] as $size){
            $sql_addsize = "INSERT INTO `chitietsanpham`(`MaSP`, `MaSize`) VALUES ('$masp', '$size')";
            mysqli_query($conn, $sql_addsize);
        }
    }

    // Thêm ảnh sản phẩm vào bảng anhsp
    $sql_addanhsp = "INSERT INTO `anhsp`(`MaSP`) VALUES ('$masp')";
    mysqli_query($conn, $sql_addanhsp);

    for($i = 1; $i <= 4; $i++){
        if($_FILES['anhsp'.$i]['name'] != ''){
            $anhsp = $_FILES['anhsp'.$i]['name'];
            $anhsp_tmp = $_FILES['anhsp'.$i]['tmp_name'];
            move_uploaded_file($anhsp_tmp, '../../webroot/image/sanpham/'.$anhsp);
            $sql_upanhsp = "UPDATE `anhsp` SET `Anh$i`='$anhsp' WHERE `MaSP`=$masp";
            mysqli_query($conn, $sql_upanhsp);
        }
    }

    header('location:../index.php?action=sanpham&view=themsp&thongbao=them');
}
	//-----------------------------------------------------------------------------------------	
			// cập nhập Sản Phẩm
if(isset($_POST['xlsua'])){
    $masp = $_POST['masp'];
    $tensp = $_POST['tensp'];
    $madm = $_POST['madm'];
    $mancc = $_POST['mancc'];
    $dongia = $_POST['dongia'];
    $mota = $_POST['mota'];

    // Cập nhật sản phẩm
    $sql_sua = "UPDATE `sanpham` SET `TenSP`='$tensp', `MaDM`=$madm, `MaNCC`=$mancc, `DonGia`=$dongia, `MoTa`='$mota' WHERE `MaSP`=$masp";
    mysqli_query($conn, $sql_sua);

    // Cập nhật ảnh nền
    if($_FILES['anhnen']['name'] != ''){
        $anhnen = $_FILES['anhnen']['name'];
        $anhnen_tmp = $_FILES['anhnen']['tmp_name'];
        move_uploaded_file($anhnen_tmp, '../../webroot/image/sanpham/'.$anhnen);
        $sql_anhnen = "UPDATE `sanpham` SET `AnhNen`='$anhnen' WHERE `MaSP`=$masp";
        mysqli_query($conn, $sql_anhnen);
    }

    // Cập nhật ảnh sản phẩm
    for($i = 1; $i <= 4; $i++){
        if($_FILES['anhsp'.$i]['name'] != ''){
            $anhsp = $_FILES['anhsp'.$i]['name'];
            $anhsp_tmp = $_FILES['anhsp'.$i]['tmp_name'];
            move_uploaded_file($anhsp_tmp, '../../webroot/image/sanpham/'.$anhsp);
            $sql_anhsp = "UPDATE `anhsp` SET `Anh$i`='$anhsp' WHERE `MaSP`=$masp";
            mysqli_query($conn, $sql_anhsp);
        }
    }

    header('location:../index.php?action=sanpham&view=suasp&masp='.$masp.'&thongbao=sua');
}
			


		


//-----------------------------------------------------------------------------------------	
//-----------------------------------------------------------------------------------------	
if(isset($_GET['xoa'])){
    $masp = $_GET['masp'];

    // Delete from phieuxuat table
    $delete4 = "DELETE FROM `phieuxuat` WHERE MaSP = $masp";
    $rs_d4 = mysqli_query($conn, $delete4);

    if($rs_d4){
        // Delete from sanphamkhuyenmai table
        $delete5 = "DELETE FROM `sanphamkhuyenmai` WHERE MaSP = $masp";
        $rs_d5 = mysqli_query($conn, $delete5);

        // Delete from chitietsanpham table
        $delete = "DELETE FROM `chitietsanpham` WHERE MaSP = $masp";
        $rs_d = mysqli_query($conn, $delete);

        if($rs_d){
            // Delete from phieunhap table
            $delete_phieunhap = "DELETE FROM `phieunhap` WHERE MaSP = $masp";
            $rs_phieunhap = mysqli_query($conn, $delete_phieunhap);

            if($rs_phieunhap){
                // Delete from anhsp table
                $delete3 = "DELETE FROM `anhsp` WHERE MaSP = $masp";
                $rs_d3 = mysqli_query($conn, $delete3);

                if($rs_d3){
                    // Delete from sanpham table
                    $delete2 = "DELETE FROM `sanpham` WHERE MaSP = $masp";
                    $rs_d2 = mysqli_query($conn, $delete2);

                    if($rs_d2){
                        header('location:../index.php?action=sanpham&thongbao=xoa');
                    } else {
                        header('location:../index.php?action=sanpham&view=themsp&thongbao=loi');
                    }
                } else {
                    header('location:../index.php?action=sanpham&view=themsp&thongbao=loi');
                }
            } else {
                header('location:../index.php?action=sanpham&view=themsp&thongbao=loi');
            }
        } else {
            header('location:../index.php?action=sanpham&view=themsp&thongbao=loi');
        }
    } else {
        header('location:../index.php?action=sanpham&view=themsp&thongbao=loi');
    }
}



	

		//-----------------------------------------------------------------------------------------	
		// Thêm màu 
		
		 ?>