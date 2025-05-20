// ✅ pagination.js
function setupPagination({
   data,
   itemsPerPage,
   containerId,
   paginationClass,
   renderRowHTML,
}) {
   let currentPage = 1;
   const totalPages = Math.ceil(data.length / itemsPerPage);

   function renderPage(page) {
      currentPage = page;
      const start = (page - 1) * itemsPerPage;
      const end = start + itemsPerPage;
      const pageData = data.slice(start, end);

      const container = document.getElementById(containerId);
      if (!container) return;

      container.innerHTML = renderRowHTML(pageData, start);

      let paginationHTML = "";
      if (page > 1)
         paginationHTML += `<button onclick="goToPage(${
            page - 1
         })">이전</button>`;
      for (let i = 1; i <= totalPages; i++) {
         paginationHTML += `<button onclick="goToPage(${i})" ${
            i === page ? 'style="font-weight:bold;"' : ""
         }>${i}</button>`;
      }
      if (page < totalPages)
         paginationHTML += `<button onclick="goToPage(${
            page + 1
         })">다음</button>`;

      document.querySelector(`.${paginationClass}`).innerHTML = paginationHTML;
   }

   window.goToPage = renderPage;
   renderPage(1);
}
