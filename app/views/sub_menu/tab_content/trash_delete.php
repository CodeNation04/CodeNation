<!-- trash_delete.php -->
<form class="task-form">
    <h3>휴지통 완전비우기 예약 등록</h3>

    <div class="form-group">
        <label>부서 선택</label>
        <select name="department">
            <option value="">부서를 선택하세요</option>
            <option value="network">네트워크팀</option>
            <option value="security">보안팀</option>
            <option value="infra">인프라팀</option>
        </select>
    </div>

    <div class="form-group">
        <label>예약작업 주기</label>
        <select name="period">
            <option value="">주기를 선택하세요</option>
            <option value="once">1회</option>
            <option value="daily">매일</option>
            <option value="weekly">매주</option>
        </select>
    </div>

    <div class="form-group">
        <label>작업 시점</label>
        <select name="schedule">
            <option value="">시점을 선택하세요</option>
            <option value="boot">부팅 시</option>
            <option value="shutdown">종료 시</option>
        </select>
    </div>

    <div class="form-group">
        <label>삭제 방식</label>
        <select name="delete_method">
            <option value="">방식을 선택하세요</option>
            <option value="kr_random">Kr Random</option>
            <option value="dod">DoD 5220.22-M</option>
        </select>
    </div>

    <div class="form-group">
        <label>덮어쓰기 횟수</label>
        <input type="number" name="overwrite_count" min="1" max="99" placeholder="예: 3회"
            style="width: 100%; padding: 6px;" />
    </div>

    <div class="form-buttons">
        <button type="submit">등록</button>
        <button type="reset">초기화</button>
    </div>
</form>