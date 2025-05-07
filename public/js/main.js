function showContent(title) {
   const content = document.getElementById("main-content");

   if (title === "관리자 정보 관리") {
      content.innerHTML = `
       
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
