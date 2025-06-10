function setupPagination({
  data,
  itemsPerPage,
  containerId,
  paginationClass,
  renderRowHTML,
}) {
  let currentPage = 1;
  const totalPages = Math.ceil(data.length / itemsPerPage);
  const pageGroupSize = 10; // 한번에 보여줄 페이지 수

  function renderPage(page) {
    currentPage = page;
    const start = (page - 1) * itemsPerPage;
    const end = start + itemsPerPage;
    const pageData = data.slice(start, end);

    const container = document.getElementById(containerId);
    if (!container) return;

    container.innerHTML = renderRowHTML(pageData, start);

    // 페이지 그룹 계산
    const currentGroup = Math.ceil(page / pageGroupSize);
    const groupStart = (currentGroup - 1) * pageGroupSize + 1;
    const groupEnd = Math.min(groupStart + pageGroupSize - 1, totalPages);

    let paginationHTML = "";

    if (page > 1) {
      paginationHTML += `<button onclick="goToPage(${page - 1})">이전</button>`;
    }

    if (groupStart > 1) {
      paginationHTML += `<button onclick="goToPage(${
        groupStart - 1
      })">◀</button>`;
    }

    for (let i = groupStart; i <= groupEnd; i++) {
      paginationHTML += `<button onclick="goToPage(${i})" ${
        i === page ? 'style="font-weight:bold;"' : ""
      }>${i}</button>`;
    }

    if (groupEnd < totalPages) {
      paginationHTML += `<button onclick="goToPage(${
        groupEnd + 1
      })">▶</button>`;
    }

    if (page < totalPages) {
      paginationHTML += `<button onclick="goToPage(${page + 1})">다음</button>`;
    }

    document.querySelector(`.${paginationClass}`).innerHTML = paginationHTML;
  }

  window.goToPage = renderPage;
  renderPage(1);
}
