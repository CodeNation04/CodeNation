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
  
          <h2>중간 관리자 목록</h2>
          <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
            <thead>
              <tr style="background-color: #f2f2f2;">
                <th style="border: 1px solid #ccc; padding: 10px;">부서</th>
                <th style="border: 1px solid #ccc; padding: 10px;">아이디</th>
                <th style="border: 1px solid #ccc; padding: 10px;">접근 IP</th>
                <th style="border: 1px solid #ccc; padding: 10px;">수정</th>
              </tr>
            </thead>
            <tbody id="manager-list">
              <tr>
                <td style="border: 1px solid #ccc; padding: 10px;">보안팀</td>
                <td style="border: 1px solid #ccc; padding: 10px;">manager1</td>
                <td style="border: 1px solid #ccc; padding: 10px;">192.168.1.100</td>
                <td style="border: 1px solid #ccc; padding: 10px;"><button>수정</button></td>
              </tr>
              <!-- 실제 DB 연동 시 반복 출력 예정 -->
            </tbody>
          </table>
  
          <h3>중간 관리자 등록</h3>
          <form onsubmit="return registerManager(event)">
            <div style="display: flex; gap: 15px; flex-wrap: wrap;">
              <div style="flex: 1; min-width: 200px;">
                <label>아이디</label><br>
                <input type="text" id="mgr-id" required style="width: 100%; padding: 8px;" />
              </div>
              <div style="flex: 1; min-width: 200px;">
                <label>비밀번호</label><br>
                <input type="password" id="mgr-pw" required style="width: 100%; padding: 8px;" />
              </div>
              <div style="flex: 1; min-width: 200px;">
                <label>접근 가능 IP</label><br>
                <input type="text" id="mgr-ip" required style="width: 100%; padding: 8px;" />
              </div>
              <div style="flex: 1; min-width: 200px;">
                <label>부서명</label><br>
                <select id="mgr-dept" style="width: 100%; padding: 8px;">
                  <option>보안팀</option>
                  <option>인사팀</option>
                  <option>개발팀</option>
                </select>
              </div>
            </div>
            <div style="text-align: right; margin-top: 20px;">
              <button type="submit" style="padding: 10px 20px; background: #2ecc71; color: white; border: none; border-radius: 5px;">등록</button>
            </div>
          </form>
        </div>