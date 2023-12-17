﻿# library
### [reservation](https://github.com/librarySQL/library/blob/main/reservation.php)
- 使用者的預約紀錄
### [seat](https://github.com/librarySQL/library/blob/main/seat.php)
- 可以查全部的座位訊息
### [user_new_reservation](https://github.com/librarySQL/library/blob/main/user_new_reservation.php) 
- 使用者可以輸入預約開始時間、結束時間、樓層、是否需要插座
-  按下查詢可預約座位後會跳轉到select_seat顯示符合條件的可預約座位
### [select_seat](https://github.com/librarySQL/library/blob/main/select_seat.php) 
- 顯示符合條件的可預約座位並跳轉到預約界面
- 讓使用者可以直接選擇想預約的座位去做預約
### [edit_reservation](https://github.com/librarySQL/library/blob/main/edit_reservation.php)
- 編輯預約紀錄(只提供編輯結束時間)
### [delete_reservation](https://github.com/librarySQL/library/blob/main/delete_reservation.php)
- 在reservation.php的頁面上按下"取消預約"，會跳出警示視窗，按下確認後，這筆訂單會被刪除
### [search_seat](https://github.com/librarySQL/library/blob/main/search_seat.php)
- 選擇座位會同步顯示此座位的預約紀錄
- 座位沒預約紀錄會**顯示尚未被預約**
### [fetch_reservation](https://github.com/librarySQL/library/blob/main/fetch_reservation.php)
- 將被選擇的座位的預約紀錄給顯示出來回傳到search_seat的頁面上做顯示
