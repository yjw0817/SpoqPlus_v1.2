<style>
.overlay {
    z-index: 999;
}

#bottom-menu-area {
    width:100%;
    border-radius: 10px 10px 0px 0px;
    border: solid 1px #bbbbbb;
    background-color:white;
}

.card-table
{
   padding: 0.2rem !important;
}

.tabmenu{
  width: 100%;
  overflow: auto;
}
.tabmenu ul{
  white-space:nowrap;
}
.tabmenu ul li{
  display: inline-block;
  padding: 0 10px;
}

#tabmenu{
    margin-top:7px;
    padding: 0px;
}

.tabmenu {
    -ms-overflow-style: none; /* IE and Edge */
    scrollbar-width: none; /* Firefox */
}
.tabmenu::-webkit-scrollbar {
    display: none; /* Chrome, Safari, Opera*/
}

.tab-pane
{
    font-size:0.9rem;
}

table#am-tb th{ background-color:#17a2b8; }
table#am-tb td{ background-color:white; }

table#am-tb thead { position: sticky; top: 0; z-index: 1; }
table#am-tb th:first-child,
table#am-tb td:first-child { position: sticky; left: 0; }

table#am-tb th:nth-child(2),
table#am-tb td:nth-child(2) { position: sticky; left: 62px; }


.main-header {
  position: sticky;
  top: 0;
  height: 57px;
  z-index: 999;
}

</style>

<style>
/* 카드 스타일 */
.ama-header-card {
    height: 165px;
    background-color: #0a53a6;
    color: white;
    width: 100%;
    padding: 20px;
    border-radius: 0 0 10px 10px;
    text-align: center;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

/* 제목 스타일 */
.ama-title {
    font-size: 22px;
    /* font-weight: bold; */
    margin-bottom: 10px;
    text-align: left; /* 왼쪽 정렬 */
}

/* 메시지 박스 스타일 */
.ama-message {
    /* margin-top: 30px; */
}

/* 메시지 박스 스타일 */
.ama-message-box {
    display: flex;
    align-items: center;
    background-color: white;
    color: black;
    padding: 10px;
    border-radius: 5px;
    margin-top: 15px;
    box-shadow: inset 0 0 5px rgba(0, 0, 0, 0.1);
    /* margin-top: 40px; */
}

/* 아이콘 스타일 */
.ama-icon {
    margin-right: 10px;
}

.ama-test {
    border: 1px solid red;
}

/* ---------------------------------------------- */

/* 통계 카드 스타일 */
.stats-container {
    display: flex;
    justify-content: center; /* stat-item들을 가운데 정렬 */
    gap: 20px;
    padding: 10px 20px;
    width: 100%; /* 가로폭 100% */
    margin: 0 auto; /* 화면 중앙에 정렬 */
}

/* 각 통계 항목 스타일 */
.stat-item {
    text-align: center;
    padding: 0 10px;
    position: relative;
}

/* 세로 구분선 스타일 */
.stat-item:not(:last-child)::after {
    content: "";
    position: absolute;
    top: 10%;
    right: 0;
    height: 80%;
    width: 1px;
    background-color: #ddd; /* 회색 구분선 */
}

/* 숫자 스타일 */
.stat-item .number {
    font-size: 2.0rem;
    font-weight: bold;
}
/* 텍스트 색상 */
.number.reservation { color: #3498db; } /* 파란색 */
.number.used { color: #000; } /* 검정색 */
.number.completed { color: #b3b3b3; } /* 회색 */
.number.recommended { color: #e74c3c; } /* 빨간색 */

/* 텍스트 스타일 */
.number-label {
    font-size: 0.9em;
    color: #999;
}

/* LIST ITEM */
.a-title { /* list title */
    font-size: 1.2rem;
    width:100%;
    text-align: center;
    margin: 15px 0 15px 0;
}

.a-warning {
    background-color: white;
    border-radius: 8px; /* 모서리를 둥글게 */
    padding: 5px;
    box-sizing: border-box;
    margin: 5px 5px 10px 5px;
    border: 1px solid #f38ba5;
    
}

.a-list {
    background-color: white;
    border-radius: 8px; /* 모서리를 둥글게 */
    padding: 5px;
    box-sizing: border-box;
    margin: 5px 5px 10px 5px;
}

.a-list2 {
    background-color: #bbdffe;
    border-radius: 8px; /* 모서리를 둥글게 */
    padding: 5px;
    box-sizing: border-box;
    margin: 5px;
}

.a-list3 {
    background-color: #fce3e9;
    border-radius: 8px; /* 모서리를 둥글게 */
    padding: 5px;
    box-sizing: border-box;
    margin: 5px;
}

.a-item {
    color: #4D4D4D;
    padding: 3px;
    margin-bottom: 5px;
}

.a-item-line {
    margin-top:10px;
    box-shadow: 0 0.2px 0 0.2px #60b6fc; /* 얇은 회색 선을 아래에만 */
}

.a-item-sec {
    display: flex;
    justify-content: space-between;
}
.a-item-sec .item-center { align-items; center; }

/* padding */
.a-item .m-l-10 { margin-left:10px; }

/* font style */
.a-item .item-bold { font-weight: bold; /* 폰트 굵게 설정 */ }
.a-item .item-light { font-weight: lighter; /* 폰트 얇게 설정 */ }
.a-item .item-cancel { text-decoration: line-through; }


/* color */
.a-item .ft-red { color: #A90647; }
.a-item .ft-default { color: #4D4D4D; }
.a-item .ft-blue { color: #0647A9; }
.a-item .ft-sky { color: #0698A9; }

.item-btn-area {
    display: flex;
}

.imp-btn {
    font-size:0.8rem;
    padding: 0px 10px 0px 10px;
    margin-right: 5px;
    border-radius: 5px;
    color:white;
    background-color:#A90647;
}

.item-btn-area .cate {
    height:26px;
    font-size:0.8rem;
    padding: 4px 10px 0px 10px;
    margin-right: 5px;
    border-radius: 20px;
    color:white;
}

.item-btn-area .btn {
    height:26px;
    font-size:0.8rem;
    padding: 4px 12px 0px 12px;
    margin-left: 5px;
    border-radius: 5px;
    color:white;
}

.item-btn-area .bg1 { background-color:#A96706; }
.item-btn-area .bg2 { background-color:#C8AA51; }

.item-btn-area .bg3 { background-color:#0698A9; }
.item-btn-area .bg4 { background-color:#4ED1E2; }
.item-btn-area .bg5 { background-color:#946EDA; }
.item-btn-area .bg6 { background-color:#CCCCCC; }

.item-btn-area .bga-cate { background-color:#C8AA51; }
.item-btn-area .bga-blue { background-color:#0698A9; }
.item-btn-area .bga-sky { background-color:#4ED1E2; }
.item-btn-area .bga-purple { background-color:#946EDA; }
.item-btn-area .bga-red { background-color:#ED5D82; }
.item-btn-area .bga-gray { background-color:#CCCCCC; }

/* schedule box */
.sch-container { display:flex; }
.sch-left {
    border:1px solid #cccccc;
    border-radius: 5px;
    width:120px;
    height: 456px;
    display:grid;
    text-align: center;
    margin-right:5px;
    background-color:#fff;
    
}
.sch-left ul { 
    list-style: none;
    padding: 0;
    margin: 0;
}

.sch-left ul li { 
    padding: 2px 0 4px 0;
}

.sch-right {
    height:456px;
    overflow-x:auto;
    overflow-y:hidden;
    white-space:nowrap;
}
.sch-right .sch-item {
    border:1px solid #cccccc;
    border-radius: 5px;
    display:inline-block;
    height:456px;
    width:200px;
    background-color:#fff;
}

.sch-right .sch-item ul { 
    list-style: none;
    padding: 0;
    margin: 0;
}


.sch-title {
    font-size:0.9rem;
    padding: 2px 10px 2px 10px;
    border-radius: 5px;
    color:white;
    background-color:#0698A9;
    text-align: center;
}

    .schedule-item {
      position: relative;
      margin-bottom: 5px;
      padding-left: 15px; /* 텍스트와 화살표 간격 */
      border-bottom: 1px solid #0698A9; /* 밑줄 */
      padding-bottom: 0px;
      width:70%;
    }

    .schedule-item .arrow-container {
      position: absolute;
      top: 14px; /* 화살표를 텍스트 위쪽에 배치 */
      left: 0;
      width: 10px;
      height: 10px; /* 화살표의 절반만 보이도록 높이를 줄임 */
      overflow: hidden; /* 나머지 절반 숨김 */
    }

    .schedule-item .arrow-container::before {
      content: "";
      position: absolute;
      top: -5px; /* 숨겨진 부분 조정 */
      left: 0;
      width: 0;
      height: 0;
      border-top: 15px solid transparent;
      border-bottom: 15px solid transparent;
      border-right: 15px solid #0698A9; /* 화살표 색상 */
    }
    
    .schedule-item2 {
      position: relative;
      margin-bottom: 5px;
      padding-left: 15px; /* 텍스트와 화살표 간격 */
      border-bottom: 1px solid #fff; /* 밑줄 */
      padding-bottom: 0px;
      width:70%;
    }
    
    .schedule-item2 .arrow-container2 {
      position: absolute;
      top: 14px; /* 화살표를 텍스트 위쪽에 배치 */
      left: 0;
      width: 10px;
      height: 10px; /* 화살표의 절반만 보이도록 높이를 줄임 */
      overflow: hidden; /* 나머지 절반 숨김 */
    }

    .schedule-item2 .arrow-container2::before {
      content: "";
      position: absolute;
      top: -5px; /* 숨겨진 부분 조정 */
      left: 0;
      width: 0;
      height: 0;
      border-top: 15px solid transparent;
      border-bottom: 15px solid transparent;
      border-right: 15px solid #fff; /* 화살표 색상 */
    }

    .schedule-time {
      color: #4D4D4D;
    }
    
    .ama-header1, .ama-header2 {
        z-index:1000;
        transition: all 0.5s ease;
    }
    
    .ama-header1 {
    position: sticky;
    top: 57px;
  }

  .ama-header2 {
    position: fixed;
    top: 57px;
    width:100%;
    border-radius: 0 0 10px 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    background-color: #e3f2fe;
    /* transform: translateY(-100%); */
    visibility: hidden;
    opacity: 0;
  }
  
  /* 스크롤 시 top1 숨기고 top2 보이기 */
  body.scrolled .ama-header1 {
    opacity: 0;
    visibility: hidden;
  }

  body.scrolled .ama-header2 {
    /* transform: translateY(0); */
    visibility: visible;
    opacity: 1;
  }

.notice-title-word {
    display: -webkit-box; /* Flexbox처럼 동작 (웹킷 기반) */
    -webkit-line-clamp: 1; /* 표시할 줄 수를 지정 */
    -webkit-box-orient: vertical; /* 수직 박스 방향 설정 */
    overflow: hidden; /* 넘치는 텍스트 숨김 */
    text-overflow: ellipsis; /* 초과 텍스트를 '...'으로 표시 */
}    

.notice-word {
    font-size:0.9rem;
    display: -webkit-box; /* Flexbox처럼 동작 (웹킷 기반) */
    -webkit-line-clamp: 3; /* 표시할 줄 수를 지정 */
    -webkit-box-orient: vertical; /* 수직 박스 방향 설정 */
    overflow: hidden; /* 넘치는 텍스트 숨김 */
    text-overflow: ellipsis; /* 초과 텍스트를 '...'으로 표시 */
}

</style>