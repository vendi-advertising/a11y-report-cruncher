<?php

declare(strict_types=1);

namespace App\Report;

use App\ReportEntity\SingleUrlRollup;
use App\ReportEntity\SingleSiteUrlCollection;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\EntityManagerInterface;

class SingleSiteRollup
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function get_sql() : string
    {
        return <<<EOD
                    SELECT
                    q1.url,
                    COUNT(
                        CASE
                            WHEN q1.discr = 'passes' THEN 1
                            ELSE NULL
                        END
                    ) as 'pass',
                    COUNT(
                        CASE
                            WHEN q1.discr = 'incomplete' THEN
                                CASE
                                    WHEN q1.impact IN ('serious', 'critical') THEN 1
                                    ELSE NULL
                                END
                            ELSE NULL
                        END
                    ) as 'incomplete-but-serious-or-critical',
                    COUNT(
                        CASE
                            WHEN q1.discr = 'violations' AND q1.impact = 'serious' THEN 1
                            ELSE NULL
                        END
                    ) as 'violation-serious',
                    COUNT(
                        CASE
                            WHEN q1.discr = 'violations' AND q1.impact = 'critical' THEN 1
                            ELSE NULL
                        END
                    ) as 'violation-critical',
                    COUNT(
                        CASE
                            WHEN q1.discr = 'violations' AND q1.impact = 'minor' THEN 1
                            ELSE NULL
                        END
                    ) as 'violation-minor',
                    COUNT(
                        CASE
                            WHEN q1.discr = 'violations' AND q1.impact = 'moderate' THEN 1
                            ELSE NULL
                        END
                    ) as 'violation-moderate'
                FROM
                    (
                        SELECT
                            su.url,
                            ar.discr,
                            arnd.impact
                        FROM
                            scan_result sr
                        LEFT JOIN
                            axe_result ar
                        ON
                            sr.id = ar.scan_result_id
                        LEFT JOIN
                            axe_result_node arn
                        ON
                            ar.id = arn.rule_result_base_id
                        LEFT JOIN
                            axe_result_node_detail arnd
                        ON
                            arn.id = arnd.rule_result_node_id
                        LEFT JOIN
                            scan_url su
                        ON
                            su.id = sr.scan_url_id
                        LEFT JOIN
                            scan s
                        ON
                            s.id = su.scan_id
                        WHERE
                            s.id = 1
                            AND
                            ar.id IN
                            (
                                SELECT
                                    ar.id
                                FROM
                                    scan_result sr
                                LEFT JOIN
                                    axe_result ar
                                ON
                                    sr.id = ar.scan_result_id
                                LEFT JOIN
                                    axe_results_tags art
                                ON
                                    ar.id = art.rule_result_base_id
                                LEFT JOIN
                                    axe_tag ats
                                ON
                                    art.tag_id = ats.id
                                LEFT JOIN
                                    scan_url su
                                ON
                                    su.id = sr.scan_url_id
                                LEFT JOIN
                                    scan s
                                ON
                                    s.id = su.scan_id
                                WHERE
                                    s.id = :scan_id
                                    AND
                                    ats.name IN ('wcag2a', 'wcag2aa')
                            )
                    ) q1
                GROUP BY
                    q1.url
                
EOD;
    }
    
    public function get_results(int $scan_id) : SingleSiteUrlCollection
    {


        $connection = $this->entityManager->getConnection();
        $statement = $connection->prepare($this->get_sql());
        $statement->bindValue('scan_id', $scan_id);
        $statement->execute();
        $results = $statement->fetchAll();

        $ret = new SingleSiteUrlCollection();

        foreach($results as $result){
            $url = new SingleUrlRollup();
            $url->url = $result['url'];
            $url->count_of_pass = (int) $result['pass'];
            $url->count_of_incomplete_but_serious_or_critical = (int) $result['incomplete-but-serious-or-critical'];
            $url->count_of_violations_serious = (int) $result['violation-serious'];
            $url->count_of_violations_critical = (int) $result['violation-critical'];
            $url->count_of_violations_minor = (int) $result['violation-minor'];
            $url->count_of_violations_moderate = (int) $result['violation-moderate'];
            $ret->add_url($url);
        }

        return $ret;
    }
}