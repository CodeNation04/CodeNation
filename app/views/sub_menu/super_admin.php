<script>
  function formSubmit(){
    const ip = document.getElementById("admin-ip").value.trim();
    const pw = document.getElementById("admin-password").value.trim();
    const pwcm = document.getElementById("admin-password-com").value.trim();
    const form = document.getElementById("superForm")

    if (!ip) {
        alert("접근 가능 IP를 입력해주세요.");
        return;
    }

    if (!pw) {
        alert("비밀번호를 입력해주세요.");
        return;
    }

    if (pw !== pwcm) {
        alert("비밀번호가 다릅니다. \n다시 입력해주세요.");
        return;
    }
    
    if(confirm("저장하시겠습니까?")){
      form.action = "/?url=SuperAdminController/superAdmin";
      form.submit();
    }
  }
</script>

<div class="placeholder" style="max-width: 900px; margin: 0 auto;">
          <h2>최고 관리자 정보</h2>
          <form id="superForm" name="superForm" method="post" style="margin-bottom: 40px;">
            <div style="margin-bottom: 15px;">
              <label><strong>아이디</strong></label><br>
              <input type="text" value="admin" name="admin_id" readonly
                style="width: 100%; padding: 10px; border: 1px solid #ccc; background-color: #f2f2f2; border-radius: 5px;" />
            </div>
            <div style="margin-bottom: 15px;">
              <label><strong>접근 가능 IP</strong></label><br>
              <input type="text" id="admin-ip" name="admin_ip" placeholder="예: 192.168.0.1,127.0.0.1"
                style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px;" />
            </div>
            <div style="margin-bottom: 15px;">
              <label><strong>새 비밀번호</strong></label><br>
              <input type="password" id="admin-password" placeholder="변경할 경우만 입력"
                style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px;" />
            </div>
            <div style="margin-bottom: 15px;">
              <label><strong>새 비밀번호 확인</strong></label><br>
              <input type="password" id="admin-password-com" placeholder="변경할 경우만 입력"
                style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px;" />
            </div>
            <div style="text-align: right;">
              <button onclick="formSubmit()" type="button"
                style="background-color: #e74c3c; color: white; padding: 10px 20px; border: none; border-radius: 5px;">저장</button>
            </div>
          </form>
  
    
        </div>