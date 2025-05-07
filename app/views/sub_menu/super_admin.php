<div class="placeholder" style="max-width: 900px; margin: 0 auto;">
          <h2>최고 관리자 정보</h2>
          <form onsubmit="return updateAdminInfo(event)" style="margin-bottom: 40px;">
            <div style="margin-bottom: 15px;">
              <label><strong>아이디</strong></label><br>
              <input type="text" value="admin" readonly
                style="width: 100%; padding: 10px; border: 1px solid #ccc; background-color: #f2f2f2; border-radius: 5px;" />
            </div>
            <div style="margin-bottom: 15px;">
              <label><strong>접근 가능 IP</strong></label><br>
              <input type="text" id="admin-ip" placeholder="예: 192.168.0.1,127.0.0.1"
                style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px;" />
            </div>
            <div style="margin-bottom: 15px;">
              <label><strong>비밀번호 변경</strong></label><br>
              <input type="password" id="admin-password" placeholder="변경할 경우만 입력"
                style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px;" />
            </div>
            <div style="text-align: right;">
              <button type="submit"
                style="background-color: #e74c3c; color: white; padding: 10px 20px; border: none; border-radius: 5px;">저장</button>
            </div>
          </form>
  
    
        </div>