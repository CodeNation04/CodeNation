function showContent(title) {
   const content = document.getElementById("main-content");

   if (title === "관리자 정보 관리") {
      content.innerHTML = `
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
      `;
   } else {
      const content = document.getElementById("main-content");
      const examplePages = [
         "부서 정보 관리",
         "감사 로그 조회",
         "예약 작업 관리",
         "삭제 환경 관리",
         "외부 반출 승인 관리",
         "Agent 정보 조회",
         "Agent 로그 조회",
      ];

      if (examplePages.includes(title)) {
         content.innerHTML = `
          <div class="placeholder">
            <h2>${title}</h2>
            <p>${title} 기능 페이지 예시입니다. 곧 구현될 예정입니다.</p>
          </div>
        `;
      } else {
         content.innerHTML = `
          <div class="placeholder">
            <h2>페이지 없음</h2>
            <p>이 탭에 대한 정의가 없습니다.</p>
          </div>
        `;
      }
   }
}

function updateAdminInfo(event) {
   event.preventDefault();
   const ip = document.getElementById("admin-ip").value.trim();
   const pw = document.getElementById("admin-password").value.trim();

   if (!ip) {
      alert("접근 가능 IP를 입력해주세요.");
      return;
   }

   console.log("최고 관리자 IP:", ip);
   console.log("비밀번호:", pw);
   alert("저장 완료 (예시)");
}

function registerManager(event) {
   event.preventDefault();

   const id = document.getElementById("mgr-id").value.trim();
   const pw = document.getElementById("mgr-pw").value.trim();
   const ip = document.getElementById("mgr-ip").value.trim();
   const dept = document.getElementById("mgr-dept").value;

   if (!id || !pw || !ip || !dept) {
      alert("모든 항목을 입력해주세요.");
      return;
   }

   console.log("[중간 관리자 등록]");
   console.log("아이디:", id);
   console.log("비밀번호:", pw);
   console.log("접근 IP:", ip);
   console.log("부서:", dept);

   alert("중간 관리자가 등록되었습니다. (예시)");
}
