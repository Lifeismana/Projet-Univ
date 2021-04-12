#ifndef ROBORALLY_ARTIFICIAL_PLAYER_HPP_
#define ROBORALLY_ARTIFICIAL_PLAYER_HPP_

#include "board.hpp"
#include "parc_graph_djik.hpp"
#include <array>
#include <vector>
#include <unordered_map>

class ArtificialPlayer
{
public:
    // Sélectionne les actions à réaliser afin d'atteindre une arrivée (en moins de 5 coups) en modifiant le tableau actions
    void jeu(RR::Robot source, RR::Robot arrive, const std::unordered_map<RR::Robot, RobotArId, RobotHash> &G, std::array<RR::Robot::Move, 9> &actions);
};

#endif