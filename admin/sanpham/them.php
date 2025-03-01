<hr class="badge-danger">
<form class="form-row" method="POST" action="sanpham/xuly.php" enctype="multipart/form-data" onsubmit="return validateForm()">
    <div class="form-group col-2"><label for="masv">Tên Sản Phẩm</label><input type="text" class="form-control" name="tensp"></div>
    <div class="form-group col-3"><label>Mã Danh Mục</label> <select name="madm" class="form-control browser-default custom-select">
        <?php $sql1="select * from danhmuc"; $rs1=mysqli_query($conn,$sql1); while ($row=mysqli_fetch_array($rs1)) { ?>
        <option value="<?php echo $row['MaDM'] ?>"><?php echo $row['MaDM'].' - '.$row['TenDM']; ?></option>
        <?php } ?>
    </select></div>

    <div class="form-group col-2"><label>Mã Thương Hiệu</label> <select name="mancc" class="form-control browser-default custom-select">
        <?php $sql2="select * from nhacc"; $rs2=mysqli_query($conn,$sql2); while ($row2=mysqli_fetch_array($rs2)) { ?>
        <option value="<?php echo $row2['MaNCC']; ?>"><?php echo $row2['MaNCC'].' - '.$row2['TenNCC']; ?></option>
        <?php } ?>
    </select></div>

    <div class="form-group col-2">
        <label>Đơn Giá</label>
        <input type="text" class="form-control" name="dongia" pattern="[0-9]+" title="Please enter a valid number" required>
    </div>
    <div class="form-group col-4"><label>Ảnh Nền</label> <input type="file" class="form-control" name="anhnen" required></div>
    <div class="form-group col-4"><label>Ảnh sản phẩm 1</label> <input type="file" class="form-control" name="anhsp1" required></div>
    <div class="form-group col-4"><label>Ảnh sản phẩm 2</label> <input type="file" class="form-control" name="anhsp2" required></div>
    <div class="form-group col-4"><label>Ảnh sản phẩm 3</label> <input type="file" class="form-control" name="anhsp3" required></div>
    <div class="form-group col-4"><label>Ảnh sản phẩm 4</label> <input type="file" class="form-control" name="anhsp4" required></div>
    <div class="form-group col-8"><label>Mô Tả</label><textarea class="form-control" name="mota" required></textarea></div>
    <div class="form-group col-12  border ">
        <label>Size :</label><br>
        <div class="btn-group col-12 row">
            <?php $sql_size="select * from size"; $rs_size=mysqli_query($conn,$sql_size); while ($kq_size=mysqli_fetch_array($rs_size)) { ?>
            <div class=" custom-checkbox custom-control col-1 ">
                <input type="checkbox" class="custom-control-input" id="size_<?php echo $kq_size['MaSize']; ?>" name="size[]" value="<?php echo $kq_size['MaSize']; ?>">
                <label class="custom-control-label" for="size_<?php echo $kq_size['MaSize']; ?>"><?php echo $kq_size['MaSize']; ?></label>
            </div>
            <?php } ?>
        </div>
    </div>
    <div class=" col-12  border ">
        <label>Màu :</label>
        <div class="form-group  ">
            <div class="btn-group  col-12 m-auto row">
                <?php $sql_mau="select * from mau"; $rs_mau=mysqli_query($conn,$sql_mau); while ($kq_mau=mysqli_fetch_array($rs_mau)) { ?>
                <div class=" custom-checkbox custom-control col-2 ">
                    <input type="checkbox" class="custom-control-input" id="mau_<?php echo $kq_mau['MaMau']; ?>" name="mau[]" value="<?php echo $kq_mau['MaMau']; ?>">
                    <label class="custom-control-label" for="mau_<?php echo $kq_mau['MaMau']; ?>"><?php echo $kq_mau['MaMau']; ?></label>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>

    <div class="form-group col-sm-6 m-auto"><br>
        <input type="submit" class="form-control badge-info" value="Thêm" name="xlthem">
    </div>
</form>
<hr>
<hr class="badge-danger">

<script>
function validateForm() {
    var sizeCheckboxes = document.querySelectorAll('input[name="size[]"]:checked');
    var colorCheckboxes = document.querySelectorAll('input[name="mau[]"]:checked');

    if (sizeCheckboxes.length === 0 || colorCheckboxes.length === 0) {
        alert('Please select at least one size and one color.');
        return false;
    }

    return true;
}
</script>