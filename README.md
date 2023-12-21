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
### [seat_reservation](https://github.com/librarySQL/library/blob/main/seat_reservation.php)
- 將使用者在search seat選擇的seat傳入並且同步顯示該座位的樓層與插座內容
- 在使用者輸入開始時間與結束時間後按下預約座位的button後就會挑出新增成功的alert並跳轉到reservation
- 預設為A1，進入頁面會直接顯示A1的狀態
### [search_seat.php(瑜珈)](https://github.com/librarySQL/library/blob/main/search_seat.php(瑜珈))
- 只列出使用者進行查詢當下那個時間以後的預約紀錄(過期的對使用者不重要的就不會顯示出來)
- 有個bug我一直修不好，下拉清單選的座位編號在按下確認之後會跳回預設值
## 管理者功能區
### [seatdetail](https://github.com/librarySQL/library/blob/main/seatdetail.php)
- 管理者登入後可以編輯、刪除、新增座位
- 此頁面是列出所有的座位清單還有三個功能的按鈕
- 刪除、新增做好了
- 剩編輯
### [newseat](https://github.com/librarySQL/library/blob/main/newseat.php)
- 管理者新增座位
### [seatdelete](https://github.com/librarySQL/library/blob/main/seatdelete.php)
- 管理者刪除座位
### [seatedit](https://github.com/librarySQL/library/blob/main/seatedit.php)
- 管理者編輯座位(尚未完成：無法順利編輯完成)
### [manage_user](https://github.com/librarySQL/library/blob/main/manage_user.php)
- 編輯還沒做
### [manage_userdelete](https://github.com/librarySQL/library/blob/main/manage_userdelete.php)
- 刪除會員帳號資料
### [manage_usercreate](https://github.com/librarySQL/library/blob/main/manage_usercreate.php)
- userId自動填入的功能還沒做，但可以成功新增
