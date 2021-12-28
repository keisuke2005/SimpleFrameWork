# 同梱内容
## フレームワーク群           (fwフォルダ内全て)
## ユーザ定義群ツリー         (skeleton.tar.gz内。starterスクリプトで展開します。)

# 使い方
## fw/starterを実行（bash）を実行してください(root実行)
### ./starter {ユーザ定義用パス}
### 例 ./starter /var/www/html

# ユーザ定義ファイル

## ルート定義ファイル
### パス ユーザ定義ルート/configs/routesRouteConfig.php
### このディレクトリ内に.phpを作れば、自動的に読み取ります。

## Controller
### パス ユーザ定義ルート/controllers/
### 命名規則
#### ファイル XXXController.php
#### クラス　 class XXXController extends (Basic|Api|SimplePage)Controller

## Model
### パス ユーザ定義ルート/models/
### 命名規則
#### ファイル XXXModel.php
#### クラス　 class XXXModel extends Model

## View
### パス ユーザ定義ルート/views/
### 命名規則
#### ファイル XXXView.php
#### クラス　 class XXXView extends (Html|Json)View

# 商用での使用はお控えください。
