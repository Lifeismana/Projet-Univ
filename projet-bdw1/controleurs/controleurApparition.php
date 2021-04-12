<?php
$connexion = getConnexionBD(); // connexion à la BD

// Quel est le tour de mister X ?
function TourMisterX ($connexion//, $??){
$requete = 'SELECT numTour FROM TourMisterX;';
$resultat= mysqli_query($connexion, $requete);
$numTour = mysqli_fetch_assoc($resultat);}

// ou est MisterX ?
$requete = 'SELECT idQArrive FROM TourMisterX;';
$resultat = mysqli_query($connexion, $requete);
$situeMisterX = mysqli_fetch_assoc($resultat);

if ($numTour == 3 || $numTour == 8 || $numTour == 13 || $numTour == 18 || $numTour == 20)
{
    echo 'MisterX si situe'.$situeMisterX ;
}



//https://codes-sources.commentcamarche.net/source/53935-exemple-d-application-de-l-algorithme-de-dijkstra-en-php
//http://theclemsweb.free.fr/script.php?url=path.php
//https://github.com/fisharebest/algorithm/blob/master/src/Dijkstra.php
// le chemin calculé : $chemin


class Dijkstra
{
    protected $graph;
    protected $distance; //Distances entre le nœud source et l'autre nœud
    protected $previous; //Le ou les nœuds précédents dans le chemin d'accès au nœud actuel
    protected $queue; //Nœuds qui n'ont pas encore été traités

    public function __construct($graph)
    {
        $this->graph = $graph;
    }

    /**
     * Process the next (i.e. closest) entry in the queue.
     *
     * @param string[] $exclude Une liste de nœuds à exclure - pour calculer les chemins les plus courts suivants.
     *
     * @return void
     */

    protected function processNextNodeInQueue(array $exclude)
    {
        //Traiter le sommet le plus proche
        $closest = array_search(min($this->queue), $this->queue);
        if (!empty($this->graph[$closest]) && !in_array($closest, $exclude)) {
            foreach ($this->graph[$closest] as $neighbor => $cost) {
                if (isset($this->distance[$neighbor])) {
                    if ($this->distance[$closest] + $cost < $this->distance[$neighbor]) {
                        // Un chemin plus court a été trouvé
                        $this->distance[$neighbor] = $this->distance[$closest] + $cost;
                        $this->previous[$neighbor] = array($closest);
                        $this->queue[$neighbor] = $this->distance[$neighbor];
                    } elseif ($this->distance[$closest] + $cost === $this->distance[$neighbor]) {
                        // Un chemin également court a été trouvé
                        $this->previous[$neighbor][] = $closest;
                        $this->queue[$neighbor] = $this->distance[$neighbor];
                    }
                }
            }
        }
        unset($this->queue[$closest]);
    }

    /**
     * Extrayez tous les chemins de $ source vers $ target en tant que tableaux de nœuds.
     *
     * @param string $target The starting node (working backwards)
     *
     * @return string[][] Un ou plusieurs chemins les plus courts, chacun représenté par une liste de nœuds
     */


    protected function extractPaths($target)
    {
        $paths = array(array($target));
        for ($key = 0; isset($paths[$key]); ++$key) {
            $path = $paths[$key];
            if (!empty($this->previous[$path[0]])) {
                foreach ($this->previous[$path[0]] as $previous) {
                    $copy = $path;
                    array_unshift($copy, $previous);
                    $paths[] = $copy;
                }
                unset($paths[$key]);
            }
        }
        return array_values($paths);
    }
    /**
     * Calculate the shortest path through a a graph, from $source to $target.
     *
     * @param string   $source  The starting node
     * @param string   $target  The ending node
     * @param string[] $exclude A list of nodes to exclude - for calculating next-shortest paths.
     *
     * @return string[][] Zero or more shortest paths, each represented by a list of nodes
     */
    public function shortestPaths($source, $target, array $exclude = array())
    {
        // La distance la plus courte à tous les nœuds commence par l'infini ...
        $this->distance = array_fill_keys(array_keys($this->graph), INF);
        // ...sauf le nœud de départ
        $this->distance[$source] = 0;
        // Les nœuds précédemment visités
        $this->previous = array_fill_keys(array_keys($this->graph), array());
        // Les nœuds précédemment visités
        $this->queue = array($source => 0);
        while (!empty($this->queue)) {
            $this->processNextNodeInQueue($exclude);
        }
        if ($source === $target) {
            // Un chemin nul
            return array(array($source));
        } elseif (empty($this->previous[$target])) {
            // Aucun chemin entre $ source et $ target
            return array();
        } else {
            // Un ou plusieurs chemins ont été trouvés entre $ source et $ target
            return $this->extractPaths($target);
        }
    }
}



echo "Le chemin le plus court pour trouver MisterX est :".$target;
