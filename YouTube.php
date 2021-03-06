<?php

/**
 * Class Search and get video information using YouTube Api .v3
 */
class YouTube {

    /**
     * api key from https://console.developers.google.com/
     * @var string
     */
    private static string $KEY ="AIzaSyD4umI-AQCbi7LhrQP-KKGMWY5yz1fJpzA";

    /**
     * @var string
     */
    private static string $URL_QUERY ="https://www.googleapis.com/youtube/v3/search?";

    /**
     * @var string
     */
    private static string $URL_VIDEO ="https://www.googleapis.com/youtube/v3/videos?";

    private static int $MAX_RESULT = 10;

    /**
     * @param $url
     * @return false|mixed
     */
    private function apiParse($url)
    {
        if(function_exists('curl_version')){
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_URL,$url);
            $result=curl_exec($ch);
            curl_close($ch);
        }
        else {
            $result = file_get_contents($url);
        }

        $data =json_decode($result, true);

        if(isset($data['items']) && count($data['items']) > 0 ){
            return $data;
        } else {
            return false;
        }

    }

    /**
     * @param $query
     * @return false|mixed
     */
    public function search($query)
    {
        $param = [
            'order' => 'date',
            'part'=> 'snippet',
            'q'=> $query,
            'type'=> 'video',
            'maxResults'=> self::$MAX_RESULT,
            'key'=> self::$KEY
        ];
        $url = self::$URL_QUERY .  http_build_query($param);

        return $this->apiParse($url);
    }

    /**
     * @param $id
     * @return int|null
     * @throws Exception
     */
    public function videoViewCount($id): ?int
    {
        $url = $this->buildUrlQuery($id, 'statistics');
        $MetaData = $this->apiParse($url);

        if ($MetaData != false && count($MetaData['items']) == 1)
        {
            return $MetaData['items'][0]['statistics']['viewCount'];
        } else {
            return null;
        }

    }

    /**
     * @param $id
     * @return string|void|null
     * @throws Exception
     */
    private function duration($id)
    {
        $url = $this->buildUrlQuery($id,'contentDetails');
        $MetaData = $this->apiParse($url);

        if ($MetaData != false){
            if(count($MetaData['items']) == 1){
                return $this->formatDuration($MetaData['items'][0]['contentDetails']['duration']);
            }
        } else {
            return null;
        }
    }

    /**
     * @param $VideoId
     * @param string $PartType
     * @return string
     */
    private function buildUrlQuery($VideoId, string $PartType = 'snippet'): string
    {
        $param = [
            'id'=> $VideoId,
            'part'=> $PartType,
            'key'=> self::$KEY
        ];

        return self::$URL_VIDEO . http_build_query($param);
    }

    /**
     * @param $duration
     * @return string
     * @throws Exception
     */
    public function formatDuration($duration): string
    {
        $FormatTime = new DateTime('@0');
        $FormatTime->add(new DateInterval($duration));

        return $FormatTime->format('H:i:s');
    }

    /**
     * @param array $result
     * @return array
     * @throws Exception
     */
    public function sortByView(array $result): array
    {
        $videos = [];
        for ($i=0;$i<count($result['items']);$i++){
            $videos[$i] ['title']= $result['items'][$i]['snippet']['title'];
            $videos[$i] ['publishedAt']= date('d-m-Y', strtotime($result['items'][$i]['snippet']['publishedAt']));
            $videos[$i] ['channelTitle']= $result['items'][$i]['snippet']['channelTitle'];
            $videos[$i] ['videoId']= $result['items'][$i]['id']['videoId'];
            $videos[$i] ['viewCount']= $this->videoViewCount($result['items'][$i]['id']['videoId']);
        }

        array_multisort(array_column($videos, 'viewCount'), SORT_DESC, $videos);

        return $videos;
    }
}
