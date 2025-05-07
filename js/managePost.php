<script>

document.addEventListener('DOMContentLoaded', function() {
  const tabs = document.querySelectorAll('#tabTinDang .nav-link');
  const items = document.querySelectorAll('.product-item, .no-product');

  function filterStatus(status) {
    items.forEach(item => {
      item.style.display = (item.dataset.status === status) ? '' : 'none';
    });
  }

  tabs.forEach(tab => {
    tab.addEventListener('click', function(e) {
      e.preventDefault();
      tabs.forEach(t => t.classList.remove('active'));
      this.classList.add('active');
      const status = this.getAttribute('data-status');
      filterStatus(status);
    });
  });

  // Auto lọc "Đang bán" khi vào trang
  filterStatus('dang_ban');
});

function capNhatTrangThai(id, loai) {
  if (!confirm("Bạn chắc chắn muốn cập nhật trạng thái này?")) return;
  fetch('api/capnhat-trangthai.php', {
    method: 'POST',
    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
    body: `id=${id}&loai=${loai}`
  })
  .then(res => res.json())
  .then(data => {
    if (data.status === 'success') {
      alert('Cập nhật thành công!');
      location.reload();
    } else {
      alert('Cập nhật thất bại!');
    }
  });
}

function suaTin(id) {
  window.location.href = `index.php?sua-tin&id=${id}`;
}

function dayTin(id) {
  if (!confirm("Bạn muốn đẩy tin này lên đầu?")) return;
  fetch('api/day-tin.php', {
    method: 'POST',
    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
    body: `id=${id}`
  })
  .then(res => res.json())
  .then(data => {
    if (data.status === 'success') {
      alert('Đẩy tin thành công!');
      location.reload();
    } else {
      alert('Đẩy tin thất bại!');
    }
  });
}

function xacNhanCapNhat(id, loai) {
  let message = '';
  if (loai === 'da_ban') {
    message = "Cảm ơn bạn đã bán sản phẩm qua trang Chợ Việt.";
  } else if (loai === 'da_an') {
    message = "Bạn xác nhận ẩn tin này và có thể mở lại khi bạn cần.";
  }

  if (confirm(message)) {
    fetch('index.php?action=capNhatTrangThai', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: `id=${id}&loai=${loai}`
    })
    .then(res => res.json())
    .then(data => {
      if (data.status === 'success') {
        alert('Cập nhật thành công!');
        setTimeout(() => {
          window.location.href = "index.php?quan-ly-tin"; // luôn đúng điều hướng
        }, 100);
      } else {
        alert(data.message || 'Cập nhật thất bại!');
      }
    });
  }
}

function xacNhanDayTin(id) {
  Swal.fire({
    title: 'Xác nhận đẩy tin?',
    text: 'Bạn sẽ mất phí 11.000 đ để đẩy tin này đến với nhiều người xem mới hơn.',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Xác nhận',
    cancelButtonText: 'Hủy'
  }).then(result => {
    if (result.isConfirmed) {
      window.location.href = 'index.php?quan-ly-tin&day=' + id;
    }
  });
}



function showDangTinModal() {
  const modal = document.getElementById('dangTinModal');
  modal.style.display = 'block';
  document.getElementById('form-dang-tin').style.display = 'block';
  document.getElementById('form-sua-tin').style.display = 'none';
  document.getElementById('modal-subtitle').innerText = 'Đăng tin mới';
  document.getElementById('backBtn').style.display = 'none';
  document.getElementById('danh-muc-cha-list').style.display = 'block';
}

function xacNhanSuaTin(id) {
  fetch(`api/getProduct.php?id=${id}`)
    .then(res => res.json())
    .then(data => {
      if (data.status === 'success') {
        const sp = data.data;

        // Hiển thị modal và form sửa
        const modal = document.getElementById('suaTinModal');
        modal.style.display = 'block';

        
        document.getElementById('form-sua-tin').style.display = 'block'; // hiện form sửa
        document.getElementById('modal-subtitle').innerText = 'Sửa tin';
        document.getElementById('backBtn').style.display = 'none';

        // Gán dữ liệu vào form sửa
        const form = document.getElementById('formSuaTin');
        form.action = `index.php?action=suaTin&id=${id}`;
        form.querySelector('input[name="tieu_de"]').value = sp.tieu_de;
        form.querySelector('input[name="gia"]').value = sp.gia;
        form.querySelector('textarea[name="mo_ta"]').value = sp.mo_ta;
        form.querySelector('input[name="id_loai_san_pham"]').value = sp.id_loai_san_pham;

        // Hiển thị ảnh hiện tại
        const previewContainer = form.querySelector('.preview-anh-cu');
        if (previewContainer) {
          previewContainer.innerHTML = ''; // clear trước
          sp.hinh_anh.split(',').forEach(name => {
            previewContainer.innerHTML += `<img src="img/${name}" width="80" style="margin: 5px; object-fit: cover;">`;
          });
        }

        // Scroll lên đầu modal
        modal.scrollTop = 0;

      } else {
        alert('Không tìm thấy sản phẩm!');
      }
    })
    .catch(error => {
      console.error('Lỗi khi lấy sản phẩm:', error);
      alert('Lỗi khi lấy dữ liệu sản phẩm.');
    });
}




</script>