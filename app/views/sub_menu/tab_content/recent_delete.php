<!-- recent_delete.php -->
<form class="task-form">
    <h3>최근 파일 삭제 예약 등록</h3>

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
        <label>삭제 대상</label><br />
        <label><input type="checkbox" name="target[]" value="win_recent" /> 윈도우 최근파일</label><br />
        <label><input type="checkbox" name="target[]" value="program_recent" /> 응용프로그램 최근파일</label><br />
        <label><input type="checkbox" name="target[]" value="usb_recent" /> USB/네트워크 드라이브</label>
    </div>

    <div class="form-buttons">
        <button type="submit">등록</button>
        <button type="reset">초기화</button>
    </div>
</form>