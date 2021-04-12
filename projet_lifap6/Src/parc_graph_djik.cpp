#include "parc_graph_djik.hpp"

#include <algorithm>
#include <array>
#include <iostream>
#include <queue>
#include <unordered_map>
#include <vector>



RobotArId::RobotArId(int _id, std::array<RR::Robot, 7> _array)
{
    id = _id;
    array = _array;
}

//le truc qu'il faut faire c'est pas ultracoolos
std::size_t RobotHash::operator()(const RR::Robot &r) const
{
    //just in case
    if (r.status == RR::Robot::Status::DEAD)
        return 0;

    std::bitset<sizeof(int) * 2 + sizeof(RR::Robot::Status)> concat;
    concat |= r.location.line;
    concat <<= 8 * sizeof(int);
    concat |= r.location.column;
    concat <<= 8 * sizeof(RR::Robot::Status);
    concat |= (int)r.status;
    return std::hash<std::bitset<sizeof(int) * 2 + sizeof(RR::Robot::Status)>>()(concat);
}

/* si seulement ça marchait :(
 std::ostream& operator<<(std::ostream& os, std::unordered_map<RR::Robot, std::array<RR::Robot,7>> &m) {
    for (const std::pair<RR::Robot, std::array<RR::Robot,7>>& p: m) {
        os<< "{" << p.first.location.line << " , "  << p.first.location.column << " , "  << (int)p.first.status << ": " << p.second.size() << "}\n";
    }
    return os;
}
*/

std::unordered_map<RR::Robot, RobotArId, RobotHash> init_graph(const RR::Board &b)
{

    std::unordered_map<RR::Robot, RobotArId, RobotHash> graph;

    //Robot mort, pas besoin de boucler dessus, tout les robots mort sont les mêmes
    RR::Robot deadRobot;
    deadRobot.location.line = 0;
    deadRobot.location.column = 0;
    deadRobot.status = RR::Robot::Status::DEAD;

    std::array<RR::Robot, 7> tabMouvementDeadRobot;
    tabMouvementDeadRobot.fill(RR::Robot(deadRobot));

    graph.insert({deadRobot, RobotArId(0, tabMouvementDeadRobot)});

    int id = 1;
    for (std::pair<RR::Location, RR::Board::TileType> tile : b.tiles)
    {
        for (RR::Robot::Status s : {RR::Robot::Status::EAST, RR::Robot::Status::NORTH, RR::Robot::Status::WEST, RR::Robot::Status::SOUTH})
        {
            RR::Robot cur;
            cur.location.line = tile.first.line;
            cur.location.column = tile.first.column;
            cur.status = s;
            std::array<RR::Robot, 7> tabMouvement;
            tabMouvement.fill(RR::Robot(cur));
            for (RR::Robot::Move m : RR::PossibleMove)
            {
                b.play(tabMouvement[(int)m], m);
            }
            graph.insert({cur, RobotArId(id, tabMouvement)});
            id++;
        }
    }
    return graph;
}

// Classe pour la priority_queue permettant de comparer les pairs pour avoir les prioritées les plus faibles en premier
class PriorityQueueCompare
{
public:
    bool operator() (std::pair<RR::Robot,int> p1,std::pair<RR::Robot,int> p2){
    return p1.second > p2.second;
    }
};

void dijkstra(RR::Robot source, const std::unordered_map<RR::Robot, RobotArId, RobotHash> &G, std::vector<int> &noeuds, std::vector<int> &distances)
{
    //make_heap pop_heap push_heap
    //priority_queue
    // création d'une file à priorité contenant des robots et leur distance depuis la source
    std::priority_queue<std::pair<RR::Robot,int>,std::vector<std::pair<RR::Robot,int>>,PriorityQueueCompare> file;

    // initialisation des vecteurs, particulièrement à la source (distance à 0 et prédesseur à id)
    noeuds.resize(G.size(), 9999);    // redimension du vecteur des prédesseurs
    distances.resize(G.size(), 9999); // redimension du vecteur des distances
    int id = G.at(source).id;
    distances[id] = 0;
    noeuds[id] = id;

    file.push(std::make_pair(source,0)); // on ajoute la source dans la file à distance 0

    // tant que la file n'est pas vide, il y a des voisins à visiter
    while (!file.empty())
    {
        std::pair<RR::Robot,int> temp_pair = file.top();
        RR::Robot cur = temp_pair.first;
        
        file.pop();
        
        int id_cur = G.at(cur).id;
        //Si noeud déjà visité on ne le revisite pas
        if(distances[id_cur] != temp_pair.second) continue;
        for (int i = 0; i < 7; i++)
        { // boucle sur les voisins du robot au sommet de la file
            RR::Robot voisin = G.at(cur).array[i];
            int poids = TabPoidsMove[i]; // Poids de l'action
            int id_voisin = G.at(voisin).id;
            
            if (distances[id_voisin] > distances[id_cur] + poids)
            { // Si on trouve un chemin plus court, on met à jour nos tables et on l'ajoute dans la file
                distances[id_voisin] = distances[id_cur] + poids; // Mise à jour de la table distances
                noeuds[id_voisin] = id_cur; // Mise à jour de la table noeuds
                file.push(std::make_pair(voisin,distances[id_voisin]));
            }
        }
    }
}

int lepluscourt(RR::Robot source, RR::Robot arrive, const std::unordered_map<RR::Robot, RobotArId, RobotHash> &G, std::vector<int> &sequence)
{
    std::vector<int> noeuds;
    std::vector<int> distances;
    dijkstra(source, G, noeuds, distances); //remplit les 2 vectors en fonction de la source

    // comparaison des distances entre les 4 orientations pour trouver le minimum
    int min_dist = 99999;
    int id_min_dist = -1;
    for (RR::Robot::Status s : {RR::Robot::Status::EAST, RR::Robot::Status::NORTH, RR::Robot::Status::WEST, RR::Robot::Status::SOUTH})
    {
        // on change le Status pour regarder toutes les orientations
        arrive.status = s;
        if (distances[G.at(arrive).id] < min_dist)
        {
            min_dist = distances[G.at(arrive).id];
            id_min_dist = G.at(arrive).id;
        }
    }
    // on n'utilise pas de stack vu que notre séquence est petite, son coût de redimensionnnement est négligeable
    // remplit la séquence dans le bon ordre
    int id_source = G.at(source).id;
    int id = id_min_dist;
    while (id_source != id)
    {
        sequence.insert(sequence.begin(), id);
        id = noeuds[id];
    }
    sequence.insert(sequence.begin(), id_source); //on rajoute la source au début
    return distances[id_min_dist];
}

void test(RR::Robot source, RR::Robot arrive, const std::unordered_map<RR::Robot, RobotArId, RobotHash> & G) {
    std::cout << "\nTest de Dijkstra et du chemin le plus court \n";

    std::cout << "Affichage de la map : \n";
    for (const std::pair<RR::Robot, RobotArId> &p : G)
    {
        std::cout << "{" << p.first << ": " << p.second.id << "}\n";
    }

    std::cout << "\nEssai dijkstra " << std::endl;
    std::vector<int> noeuds;
    std::vector<int> distances;
    dijkstra(source,G,noeuds,distances);

    std::cout << "Pour la source à la colonne " << source.location.column << " et ligne " << source.location.line << " avec comme id " << G.at(source).id << std::endl;
    std::cout << "Arrivé à l'id " << G.at(arrive).id << "  distance : " << distances[G.at(arrive).id] << " et pred : " << noeuds[G.at(arrive).id] << std::endl;

    std::vector<int> s;
    int min = lepluscourt(source,arrive,G,s);

    std::cout << "\nChemin le plus court pour une distance " << min << std::endl;
    for(int elem : s){
        std::cout << "Passe par les cases d'id " << elem << std::endl;
    }
}
