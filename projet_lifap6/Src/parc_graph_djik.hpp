#ifndef ROBORALLY_TEST_HPP_
#define ROBORALLY_TEST_HPP_

#include "board.hpp"
#include <array>
#include <vector>
#include <unordered_map>

// Tableau des poids de chaque action
static const int TabPoidsMove[7]={
    1,//RR::Robot::Move::FORWARD_1,
    5,//RR::Robot::Move::FORWARD_2,
    10,//RR::Robot::Move::FORWARD_3,
    20,//RR::Robot::Move::BACKWARD_1,
    5,//RR::Robot::Move::TURN_LEFT,
    5,//RR::Robot::Move::TURN_RIGHT,
    20,//RR::Robot::Move::U_TURN};
};

// Contient un id unique en fonction de la position et la direction du robot, et un tableau de ses 7 positions en fonction des 7 mouvements possibles
struct RobotArId
{
    int id;
    std::array<RR::Robot, 7> array;
    RobotArId(int _id, std::array<RR::Robot, 7> _array);
};

struct RobotHash
{
    std::size_t operator()(const RR::Robot &l) const;
};

// Initialisation du graphe
std::unordered_map<RR::Robot, RobotArId, RobotHash> init_graph(const RR::Board &b);

// Remplit une distance et un nœud de provenance pour chaque nœud d'un graphe G(corrspondant au plus court parcours), à partir d'un robot source
void dijkstra(RR::Robot robot, const std::unordered_map<RR::Robot, RobotArId, RobotHash> &G, std::vector<int> &noeuds, std::vector<int> &distances);

// Remplit une séquence de coups minimales pour aller de la source à une arrivée sans prendre en compte la direction de l'arrivée, renvoie la distance minimale
int lepluscourt(RR::Robot source, RR::Robot arrive, const std::unordered_map<RR::Robot, RobotArId, RobotHash> &G, std::vector<int> &sequence);

// Test de cas simples de l'algorithme de Dijkstra et du chemin le plus court
void test(RR::Robot source, RR::Robot arrive, const std::unordered_map<RR::Robot, RobotArId, RobotHash> & G);
#endif