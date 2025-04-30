function showContent(title) {
   const content = document.getElementById("main-content");

   if (title === "관리자 정보 관리") {
      content.innerHTML = `
        <div class="placeholder" style="max-width: 600px; margin: 0 auto;">
          <h2 style="margin-bottom: 30px;">관리자 정보 관리</h2>
          <form onsubmit="return updateAdminInfo(event)">
            <div style="margin-bottom: 20px;">
              <label style="font-weight: bold;">아이디</label><br>
              <input type="text" value="admin" readonly 
                style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px; background-color: #f2f2f2;" />
            </div>
            <div style="margin-bottom: 20px;">
              <label style="font-weight: bold;">접근 가능 IP</label><br>
              <input type="text" id="admin-ip" placeholder="예: 192.168.0.1,127.0.0.1" 
                style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px;" />
            </div>
            <div style="margin-bottom: 20px;">
              <label style="font-weight: bold;">새 비밀번호</label><br>
              <input type="password" id="admin-password" placeholder="변경할 경우만 입력" 
                style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px;" />
            </div>
            <div style="text-align: right;">
              <button type="submit" 
                style="background-color: #e74c3c; color: white; padding: 10px 25px; border: none; border-radius: 5px; font-weight: bold; cursor: pointer;">
                저장
              </button>
            </div>
          </form>
        </div>
      `;
   } else if (title === "부서 정보 관리") {
      content.innerHTML = `
        <div class="placeholder">
          <h2>부서 정보 관리</h2>
          <form onsubmit="return saveDepartmentInfo(event)">
            <div style="margin-bottom: 15px;">
              <label>부서명</label><br>
              <input type="text" id="dept-name" style="width: 100%; padding: 8px;" required />
            </div>
            <div style="margin-bottom: 15px;">
              <label>담당자명</label><br>
              <input type="text" id="dept-manager" style="width: 100%; padding: 8px;" />
            </div>
            <div style="margin-bottom: 15px;">
              <label>연락처 (전화번호)</label><br>
              <input type="text" id="dept-phone" style="width: 100%; padding: 8px;" />
            </div>
            <div style="margin-bottom: 15px;">
              <label>이메일</label><br>
              <input type="email" id="dept-email" style="width: 100%; padding: 8px;" />
            </div>
            <div style="margin-bottom: 15px;">
              <label>비고</label><br>
              <textarea id="dept-note" rows="3" style="width: 100%; padding: 8px;"></textarea>
            </div>
            <button type="submit" style="padding: 10px 20px; background: #3498db; color: white; border: none; border-radius: 4px;">저장</button>
          </form>
        </div>
      `;
   } else {
      // 예시 탭 연결
      const examplePages = [
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
            <p>👉 ${title} 기능 페이지 예시입니다. 곧 구현될 예정입니다.</p>
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
   const password = document.getElementById("admin-password").value.trim();
   if (!ip) {
      alert("접근 가능 IP를 입력해주세요.");
      return false;
   }
   console.log("접근 가능 IP:", ip);
   console.log("비밀번호:", password);
   alert("관리자 정보가 저장되었습니다. (예시)");
   return false;
}

function saveDepartmentInfo(event) {
   event.preventDefault();
   const name = document.getElementById("dept-name").value.trim();
   const manager = document.getElementById("dept-manager").value.trim();
   const phone = document.getElementById("dept-phone").value.trim();
   const email = document.getElementById("dept-email").value.trim();
   const note = document.getElementById("dept-note").value.trim();
   if (!name) {
      alert("부서명을 입력해주세요.");
      return false;
   }
   console.log("부서명:", name);
   console.log("담당자:", manager);
   console.log("전화번호:", phone);
   console.log("이메일:", email);
   console.log("비고:", note);
   alert("부서 정보가 저장되었습니다. (예시)");
   return false;
}
