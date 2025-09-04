<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        $region = strtoupper($request->query('region', 'BR'));
        $limit  = (int) $request->query('limit', 20);

        // Fontes gratuitas (RSS oficiais)
        // BR (geral jurídico/legislativo)
        $feedsBR = [
            // STJ – Notícias
            'STJ'    => 'https://res.stj.jus.br/hrestp-c-portalp/RSS.xml', // :contentReference[oaicite:0]{index=0}
            // Senado – todas as notícias
            'Senado' => 'https://www12.senado.leg.br/noticias/feed/todasnoticias', // :contentReference[oaicite:1]{index=1}
            // Agência Brasil – editoria Justiça
            'Agência Brasil (Justiça)' => 'https://agenciabrasil.ebc.com.br/feed/justica', // página de feeds :contentReference[oaicite:2]{index=2}
            // Câmara dos Deputados – (escolha um tema em /noticias/rss)
            // Ex.: Política Legislativa/Justiça (ajuste conforme preferência)
            // 'Câmara' => '<<cole aqui a URL de tema do RSS escolhido>>', // página de RSS :contentReference[oaicite:3]{index=3}
        ];

        // SC (sem feed oficial único do TJSC; usaremos Brasil + possibilidade de acrescentar OAB-SC, TRF4, etc.)
        $feedsSC = [
            // Você pode incluir fontes regionais que publiquem RSS.
            // Ex.: sindicatos, OAB-SC subseções, TRF4 páginas com RSS, etc.
            // Como fallback, usamos as nacionais acima para cobrir SC quando não houver feed próprio.
        ];

        $feeds = $region === 'SC' && !empty($feedsSC) ? $feedsSC : $feedsBR;
        $cacheKey = 'news:'.md5(json_encode($feeds)).":$limit";

        $items = Cache::remember($cacheKey, now()->addMinutes(10), function () use ($feeds, $limit) {
            $all = [];
            foreach ($feeds as $source => $url) {
                try {
                    $xmlStr = @file_get_contents($url);
                    if (!$xmlStr) continue;
                    $xml = @simplexml_load_string($xmlStr, 'SimpleXMLElement', LIBXML_NOCDATA);
                    if (!$xml) continue;

                    $entries = [];
                    if (isset($xml->channel->item)) {
                        $entries = $xml->channel->item;
                    } elseif (isset($xml->entry)) {
                        $entries = $xml->entry;
                    }

                    foreach ($entries as $e) {
                        $title = (string)($e->title ?? '');
                        $link  = (string)($e->link['href'] ?? $e->link ?? '');
                        $desc  = (string)($e->description ?? $e->summary ?? '');
                        $date  = (string)($e->pubDate ?? $e->updated ?? $e->published ?? '');

                        $all[] = [
                            'source' => $source,
                            'title'  => trim($title),
                            'link'   => trim($link),
                            'desc'   => trim(strip_tags($desc)),
                            'date'   => $date ? date('c', strtotime($date)) : null,
                        ];
                    }
                } catch (\Throwable $th) {
                  
                }
            }

            usort($all, function ($a, $b) {
                return strtotime($b['date'] ?? '1970-01-01') <=> strtotime($a['date'] ?? '1970-01-01');
            });

            return array_slice($all, 0, $limit);
        });

        return response()->json([
            'region' => $region,
            'count'  => count($items),
            'items'  => $items,
        ]);
    }
}
