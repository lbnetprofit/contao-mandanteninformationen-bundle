<?php
namespace Netprofit\ContaoMandanteninformationenBundle\Controller\FrontendModule;

use Contao\CoreBundle\Controller\FrontendModule\AbstractFrontendModuleController;
use Contao\CoreBundle\Exception\PageNotFoundException;
use Contao\CoreBundle\Routing\ResponseContext\HtmlHeadBag\HtmlHeadBag;
use Contao\CoreBundle\ServiceAnnotation\FrontendModule;
use Contao\Config;
use Contao\Environment;
use Contao\Input;
use Contao\ModuleModel;
use Contao\PageModel;
use Contao\Pagination;
use Contao\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @FrontendModule(MandanteninformationenController::TYPE, category="miscellaneous", template="mod_mandanteninformationen")
 */
class MandanteninformationenController extends AbstractFrontendModuleController{

    public const TYPE = 'mandanteninformationen';

    protected function getResponse(Template $template, ModuleModel $model, Request $request): Response{
		if($template->customTpl != "mod_mandanteninformationen"){
			$template->class .= " ". $template->customTpl;
		}

		$data = static::getMandanteninformationen();
		$template->branding = str_replace('a target="_blank"', 'a target="_blank" rel="noopener"', $data['bereitgestellt'][0]);

		$intTotal = sizeof($data['news']);
		if ($intTotal > 0){
			$offset = 0;
			$total = $intTotal - $offset;
			if ($template->numberOfItems > 0){
				$limit = $template->numberOfItems;
			}
			if ($template->perPage > 0 && (!isset($limit) || $total > $template->perPage)){
				if (isset($limit)){
					$total = min($limit, $total);
				}
				$id = 'page_n' . $template->id;
				$page = Input::get($id) ?? 1;
				if ($page < 1 || $page > max(ceil($total/$template->perPage), 1)){
					throw new PageNotFoundException('Page not found: ' . Environment::get('uri'));
				}

				$limit = $template->perPage;
				$offset += (max($page, 1) - 1) * $template->perPage;

				if ($offset + $limit > $total){
					$limit = $total - $offset;
				}

				$pagination = new Pagination($total, $template->perPage, Config::get('maxPaginationLinks'), $id);
				$template->pagination = $pagination->generate("\n  ");
			}

			$news = array_slice($data['news'], $offset, (isset($limit) ? $limit : null));
			if ($news !== null){
				$template->news = $news;
			}
		}
		return $template->getResponse();
    }

	function getMandanteninformationen(){
		if (function_exists('curl_version')) {
			$ctx = curl_init();
			curl_setopt($ctx, CURLOPT_URL, "https://steuerberaterschnittstelle.mrr-web.de/index.php?token=" . Config::get("mandanteninformationen_token"));
			curl_setopt($ctx, CURLOPT_RETURNTRANSFER, TRUE);
			$data = curl_exec($ctx);
			curl_close($ctx);
		} else {
			if (ini_get('allow_url_fopen')) {
				$data = file_get_contents($uri);
			} else {
				die("Neither curl nor file_get_contents is available.");
			}
		}
		
		$data = str_replace('ï»¿', '', utf8_encode($data));
		$data = json_decode($data, true);
		
		return $data;
	}

}