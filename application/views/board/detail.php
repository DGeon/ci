<?php
$pageNum = $_GET['pageNum'];
$searchMenu = $_GET['searchMenu'];
$keyword = $_GET['keyword'];
?>
<!DOCTYPE html>
<html lang="ko">
<?php $this->load->view('common/head') ?>

<head>
    <style>
        table {
            margin: auto;
        }

        table tr {
            height: 100px;
        }

        table tr:nth-child(4) td {
            text-align: right;
            padding-right: 10px;
        }

        input,
        textarea {
            width: 100%;
        }
    </style>
    <script>
        $(document).ready(function() {
            $("button[name='btn-board-delete']").hide();
            $("button[name='btn-board-update']").hide();
            var writer = <?= json_encode($row['writer']); ?>;
            var id = <?= isset($_COOKIE['id']) ? json_encode($_COOKIE['id']) : 'false' ?>;
            if (writer === id && id) {
                $("button[name='btn-board-update']").show();
            }
        });

        function boardList() {

            window.location.href = 'board?pageNum=' + <?= json_decode($pageNum) ?> + '&searchMenu=' + <?= json_encode($searchMenu) ?> + '&keyword=' + <?= json_encode($keyword) ?>;

        }
        function boardUpdate() {
            var writer = <?= json_encode($row['writer']); ?>;
            var id = <?= isset($_COOKIE['id']) ? json_encode($_COOKIE['id']) : 'false' ?>;
            if (writer === id) {
                $("button[name='btn-board-delete']").show();
            } else {
                $("button[name='btn-board-delete']").hide();
            }
            if (!$("input[name='title']").prop("disabled")) {
                var bno = <?= $row['bno']; ?>;
                var writer = <?= json_encode($row['writer']); ?>;
                var title = $("input[name='title']").val();
                var content = $("textarea[name='content']").val();
                $.ajax({
                    url: "boardUpdate",
                    type: "POST",
                    dataType: "json",
                    data: {
                        bno: bno,
                        writer: writer,
                        title: title,
                        content: content,
                    },
                    success: function(response) {
                        alert(response.msg);
                        $("input[name='title']").attr("disabled", true);
                        $("textarea[name='content']").attr("disabled", true);
                        $("button[name='btn-board-delete']").hide();
                    },
                    error: function(xhr, status, error) {
                        console.error("상태: " + status);
                        console.error("에러: " + error);
                        console.error("응답 텍스트: " + xhr.responseText);

                        alert("서버와 통신 중 오류가 발생했습니다.");
                    }
                });
            }
            $("input[name='title']").attr("disabled", false);
            $("textarea[name='content']").attr("disabled", false);

        }

        function boardDelete() {
            var writer = <?= json_encode($row['writer']); ?>;
            var id = <?= isset($_COOKIE['id']) ? json_encode($_COOKIE['id']) : 'false' ?>;
            var bno = <?= json_encode($row['bno']); ?>;
            if (writer === id) {
                $.ajax({
                    url: "boardDelete",
                    type: "POST",
                    dataType: "json",
                    data: {
                        writer: writer,
                        bno: bno
                    },
                    success: function(response) {
                        alert(response.msg);
                        window.location.href = "board";
                    },
                    error: function(xhr, status, error) {
                        console.error("상태: " + status);
                        console.error("에러: " + error);
                        console.error("응답 텍스트: " + xhr.responseText);

                        alert("서버와 통신 중 오류가 발생했습니다. 콘솔에서 오류를 확인하세요.");
                    }

                });
            } else {
                alert("회원정보와 다릅니다.");
            }
        }
    </script>
</head>

<body>
    <?php $this->load->view('common/header') ?>
    <table style="width : 600px">
        <tr>
            <td>글제목</td>
            <td><input type="text" name="title" value="<?= $row['title']; ?>" disabled></input></td>
        </tr>
        <tr>
            <td>작성자</td>
            <td><input type="text" name="writer" value="<?= $row['writer']; ?>" disabled></td>
        </tr>
        <tr>
            <td>내 용</td>
            <td>
                <textarea name="content" disabled><?= $row['content']; ?></textarea>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <button type="button" name="btn-board-delete" onclick="boardDelete()">삭제하기</button>
                <button type="button" name="btn-board-submit" onclick="boardList()">목록으로</button>
                <button type="button" name="btn-board-update" onclick="boardUpdate()">수정하기</button>
            </td>
        </tr>
    </table>
    <?php $this->load->view('common/footer') ?>
</body>

</html>