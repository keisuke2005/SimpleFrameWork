<?php
namespace FW\Foundation;

/**
* MVC情報データオブジェクト
*
* MVCに関わる情報を保管するクラス
* @access public
* @author keisuke <ukei2021@gmail.com>
* @copyright MezzoDay Corporation All Rights Reserved
* @version 1.0
* @package FW\Foundation
*/
class MvcInfo
{
  private string $apl_url_path;
  private string $request_url_path;
  private string $application_path;
  private string $frame_work_path;

  /**
  * コンストラクタ
  *
  * Requestオブジェクトを基に、パス情報を作る
  * @access public
  * @param Request $request
  * @param string $application_path
  * @return void
  */
  public function __construct(Request $request,string $application_path)
  {
    $this->apl_url_path = $this->make_apl_url_path($request);
    $this->request_url_path = $this->make_request_url_path($request);
    $this->application_path = $application_path;
    $this->frame_work_path = dirname(__DIR__);
  }

  /**
  * $apl_url_path Getter
  * @access protected
  * @return string
  */
  public function get_apl_url_path()
  {
    return $this->apl_url_path;
  }

  /**
  * $request_url_path Getter
  * @access protected
  * @return string
  */
  public function get_request_url_path()
  {
    return $this->request_url_path;
  }

  /**
  * $application_path Getter
  * @access protected
  * @return string
  */
  public function get_application_path()
  {
    return $this->application_path;
  }

  /**
  * $frame_work_path Getter
  * @access protected
  * @return string
  */
  public function get_frame_work_path()
  {
    return $this->frame_work_path;
  }

  /**
  * リクエストパス取得
  *
  * クエリパラメータを除外したものに成形して取得する。
  *
  * https://apl.com:8443/aplname/xxx/yyy?zzz=1 => /aplname/xxxx/yyy
  * @access private
  * @param Request $request
  * @return string
  */
  private function make_request_url_path(Request $request): string
  {
    return substr($request->get_request_uri(), 0, strcspn($request->get_request_uri(),'?'));
  }

  /**
  * ベースリクエストパス取得
  *
  * アプリのルートurlにあたる
  *
  * https://apl.com:8443/aplname/xxx/yyy?zzz=1 => /aplname
  * @access private
  * @param Request $request
  * @return string
  */
  private function make_apl_url_path(Request $request): string
  {
    return $request->get_context_prefix().str_replace($request->get_context_document_root(),'',getcwd());
  }
}
