# DBWork

DBの管理はPHPが相性いいと言われてたので色んな動画やサイトを見ながら勉強をし作ってみた

次はExpress + Node.jsで作ってみようかなとか考えてます。

# テーブル構成

User
・ id
・ user_no
・ name
・ password
・ auth_type

work
・ id
・ user_id
・ date
・ start_time
・ end_time
・ break_time

# 画面構成

ログイン画面
![スクリーンショット (439)](https://user-images.githubusercontent.com/67303349/123573080-3c6c4a80-d808-11eb-8e37-3df84be1568b.png)

![スクリーンショット (437)](https://user-images.githubusercontent.com/67303349/123573105-4b52fd00-d808-11eb-8d6c-2c1097e698dc.png)


編集を押すと勤怠報告が面が出てくるので入力して登録することで確定できる
また今回は何度も編集は可能である。

管理者用のログイン画面
![スクリーンショット (440)](https://user-images.githubusercontent.com/67303349/123573373-c5838180-d808-11eb-9a10-6ea9885b22d2.png)
![スクリーンショット (441)](https://user-images.githubusercontent.com/67303349/123573374-c61c1800-d808-11eb-8ee8-bb20f9c31eb6.png)

社員一人一人の勤怠状況が見れ管理ができる

![スクリーンショット (442)](https://user-images.githubusercontent.com/67303349/123573376-c74d4500-d808-11eb-8a6a-ea134c1a2d98.png)

