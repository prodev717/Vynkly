<?php 
    function pagedata($url){
        $dom = new DOMDocument();
        if(@$dom->loadHTMLFile($url)){
            $title = $dom->getElementsByTagName("title")->item(0)->textContent ?? "";
            $description = '';
            $keywords = '';
            $author = '';
            $tags = $dom->getElementsByTagName("meta");
            foreach($tags as $tag){
                $nameAttr = strtolower($tag->getAttribute("name"));
                switch ($nameAttr) {
                    case "description":
                        $description = $tag->getAttribute("content");
                        break;
                    case "keywords":
                        $keywords = $tag->getAttribute("content");
                        break;
                    case "author":
                        $author = $tag->getAttribute("content");
                        break;
                }
            }
            return [
                "url" => $url,
                "title" => $title,
                "description" => $description,
                "author" => $author,
                "keywords" => $keywords
            ];
        }
        return null;
    }
    
    class Websites extends SQLite3 {
        public function __construct(){
            $this->open("websites.db");
            $this->exec("CREATE TABLE IF NOT EXISTS websites (
                id INTEGER PRIMARY KEY AUTOINCREMENT, 
                url TEXT UNIQUE, 
                title TEXT, 
                description TEXT,
                author TEXT, 
                keywords TEXT
            );");
        }
        public function __destruct(){
            $this->close();
        }
        public function create($url, $title, $description, $author, $keywords){
            $stmt = $this->prepare("INSERT OR IGNORE INTO websites 
                (url, title, description, author, keywords)
                VALUES (:url, :title, :description, :author, :keywords);");
            $stmt->bindValue(":url", $url, SQLITE3_TEXT);
            $stmt->bindValue(":title", $title, SQLITE3_TEXT);
            $stmt->bindValue(":description", $description, SQLITE3_TEXT);
            $stmt->bindValue(":author", $author, SQLITE3_TEXT);
            $stmt->bindValue(":keywords", $keywords, SQLITE3_TEXT);
            return $stmt->execute();
        }
        public function getAll($page = 1) {
            $perPage = 5;
            $offset = ($page - 1) * $perPage;
            $stmt = $this->prepare("SELECT * FROM websites ORDER BY id DESC LIMIT :limit OFFSET :offset");
            $stmt->bindValue(':limit', $perPage, SQLITE3_INTEGER);
            $stmt->bindValue(':offset', $offset, SQLITE3_INTEGER);
            $result = $stmt->execute();
            $cstmt = $this->prepare("SELECT COUNT(*) AS total FROM websites;");
            $count = $cstmt->execute();
            $total = $count->fetchArray(SQLITE3_ASSOC)["total"];
            $websites = [];
            while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
                $websites[] = $row;
            }
            return [
                "data" => $websites,
                "current_page" => $page,
                "per_page" => $perPage,
                "total_pages" => ceil($total/$perPage),
                "total_results" => $total
            ];
        }
        public function find($input, $page = 1) {
            $perPage = 5;
            $offset = ($page - 1) * $perPage;
            $clean = preg_replace('/[^a-zA-Z0-9 ]/', '', strtolower($input));
            $words = array_filter(explode(" ", $clean));
            if (empty($words)) {
                return [
                    "data" => [],
                    "current_page" => $page,
                    "per_page" => $perPage,
                    "total_pages" => 0,
                    "total_results" => 0
                ];
            }
            $selectParts = [];
            $whereParts = [];
            foreach ($words as $i => $word) {
                $safeWord = "%".SQLite3::escapeString($word)."%";
                $selectParts[] = "(title LIKE '$safeWord') + (description LIKE '$safeWord') + (keywords LIKE '$safeWord') + (url LIKE '$safeWord') + (author LIKE '$safeWord')";
                $whereParts[] = "(title LIKE '$safeWord' OR description LIKE '$safeWord' OR keywords LIKE '$safeWord' OR url LIKE '$safeWord' OR author LIKE '$safeWord')";
            }
            $matchScore = implode(" + ", $selectParts);
            $whereClause = implode(" OR ", $whereParts);
            $sql = "
                SELECT *, ($matchScore) AS match_score
                FROM websites
                WHERE $whereClause
                ORDER BY match_score DESC, id DESC
                LIMIT $perPage OFFSET $offset;
            ";
            $result = $this->query($sql);
            $cstmt = $this->prepare("SELECT COUNT(*) AS total FROM websites WHERE $whereClause;");
            $count = $cstmt->execute();
            $total = $count->fetchArray(SQLITE3_ASSOC)["total"];
            $results = [];
            while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
                $results[] = $row;
            }
            return [
                "data" => $results,
                "current_page" => $page,
                "per_page" => $perPage,
                "total_pages" => ceil($total/$perPage),
                "total_results" => $total
            ];
        }
    }
?>