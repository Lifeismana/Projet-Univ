#include "artificial_player.hpp"
#include "board.hpp"
#include "parc_graph_djik.hpp"

#include <unordered_set>
#include <vector>
#include <algorithm>


std::vector<RR::Robot::Move> sequencesMin(const RR::Robot & arrive, const std::unordered_map<RR::Robot, RobotArId,RobotHash> & G, RR::Robot source, std::vector<RR::Robot::Move> actionsToUse) {
    // cas d'arret --> l'objectif est atteint ou 5 cartes ont été jouées
    if (source == arrive || actionsToUse.size() < 9 - 5){
        return std::vector<RR::Robot::Move>();
    }
    
    std::unordered_set<RR::Robot::Move> distinct_action(actionsToUse.begin(), actionsToUse.end());

    std::vector<RR::Robot::Move> action_min_dist;
    int min_dist = 99999;
    // on cherche l'action qui nous rapproche potientiellement le plus de l'arrivée
    for(RR::Robot::Move action : distinct_action){
        std::vector<int> sequence;
        RR::Robot new_source = G.at(source).array[(int)action];
        if(new_source.status==RR::Robot::Status::DEAD)continue; //Si l'action ne mène pas en dehors de G, on calcule le minimum
        int min = lepluscourt(new_source,arrive,G,sequence);
        if(min <= min_dist){
            if(min < min_dist){
                action_min_dist.clear();
            }
            min_dist = min;
            action_min_dist.push_back(action);
        }
    }

    std::vector<std::vector<RR::Robot::Move>> tab_sequences;

    for(RR::Robot::Move action : action_min_dist){
        RR::Robot new_source = G.at(source).array[(int)action];

        std::vector<RR::Robot::Move> new_actions(actionsToUse);
        new_actions.erase(std::find(new_actions.begin(),new_actions.end(),action));

        std::vector<RR::Robot::Move> tabaction(sequencesMin(arrive,G,new_source,new_actions));
        tabaction.insert(tabaction.begin(),action);
        tab_sequences.push_back(tabaction);
    }

    int min_size=99999;
    std::vector<RR::Robot::Move> sequence_to_return;
    
    for(std::vector<RR::Robot::Move> sequence : tab_sequences){
        int min = sequence.size(); 
        if(min < min_size){
            min_size = min;
            sequence_to_return = sequence;
        }
    }
    
    return sequence_to_return;
}

void ArtificialPlayer::jeu(RR::Robot source, RR::Robot arrive,const std::unordered_map<RR::Robot, RobotArId,RobotHash> & G, std::array<RR::Robot::Move,9> & actions) {
    std::vector<RR::Robot::Move> sequence;
    
    std::vector<RR::Robot::Move> actionsCopy;
    actionsCopy.reserve(9);
    std::copy(actions.begin(), actions.end(), std::back_inserter(actionsCopy));

    //on cherche la séquence qu'on veut jouer
    sequence = sequencesMin(arrive,G,source,actionsCopy);
    // on réordonne notre tableau d'action pour avoir nos actions au début;
    int num=0;
    for (RR::Robot::Move element : sequence){
        // échange des éléments entre les premiers éléments d'actions et la séquence 
        std::iter_swap(actions.begin()+num,std::find(actions.begin()+num,actions.end(),element));
        num++;
    }
    std::cout<<"\nRenvoi du tableau trié"<<std::endl;
}



int main(int argc, const char** argv) {
    RR::Board b("board.txt") ;
    std::unordered_map<RR::Robot, RobotArId,RobotHash> graph;
    graph = init_graph(b);

    RR::Robot source; // robot posé ligne 5, colonne 4 et dirigé vers le nord (id = 2)
    source.location.line = 5;
    source.location.column = 4;
    source.status = RR::Robot::Status::NORTH;

    RR::Robot arrive; // robot posé ligne 2, colonne 2 et dirigé vers le nord (id = 50)
    arrive.location.line = 1;
    arrive.location.column = 2;
    arrive.status = RR::Robot::Status::NORTH;

    RR::Robot arrive2; // robot arrivé posé ligne 1, colonne 4 et dirigé vers l'est (id = 33)
    arrive2.location.line = 1;
    arrive2.location.column = 4;
    arrive2.status = RR::Robot::Status::EAST;

    test(source, arrive, graph);

    std::cout << "\nTest joueur artificiel \n";
 
    std::array<RR::Robot::Move,9> actions {
        RR::Robot::Move::FORWARD_2,
        RR::Robot::Move::TURN_RIGHT,
        RR::Robot::Move::FORWARD_1,
        RR::Robot::Move::BACKWARD_1,
        RR::Robot::Move::BACKWARD_1,
        RR::Robot::Move::FORWARD_3,
        RR::Robot::Move::FORWARD_2,
        RR::Robot::Move::FORWARD_1,
        RR::Robot::Move::BACKWARD_1};

    std::array<RR::Robot::Move,9> actions1 {
        RR::Robot::Move::BACKWARD_1,
        RR::Robot::Move::TURN_RIGHT,
        RR::Robot::Move::BACKWARD_1,
        RR::Robot::Move::BACKWARD_1,
        RR::Robot::Move::BACKWARD_1,
        RR::Robot::Move::FORWARD_3,
        RR::Robot::Move::FORWARD_2,
        RR::Robot::Move::FORWARD_1,
        RR::Robot::Move::TURN_LEFT};

    std::array<RR::Robot::Move,9> actions2 {
        RR::Robot::Move::BACKWARD_1,
        RR::Robot::Move::FORWARD_3, 
        RR::Robot::Move::BACKWARD_1, 
        RR::Robot::Move::FORWARD_3, 
        RR::Robot::Move::TURN_LEFT, 
        RR::Robot::Move::TURN_LEFT,
        RR::Robot::Move::TURN_LEFT,
        RR::Robot::Move::TURN_LEFT,
        RR::Robot::Move::TURN_LEFT};


    // Test d'un tour qui atteint l'objectif en 2 coups

    ArtificialPlayer j1;
    j1.jeu(source, arrive, graph, actions);
    std::cout << "\nRéorganisation des actions de j1 : \n";
    for(RR::Robot::Move c : actions) {
        std::cout << "Carte " << c << std::endl;
    }
   
    
    // Test tour qui atteint l'objectif en 1 coup

    ArtificialPlayer j2;
    j2.jeu(source, arrive2, graph, actions1);
    std::cout << "\nRéorganisation des actions de j2 : \n";
    for(RR::Robot::Move c : actions1) {
        std::cout << "Carte " << c << std::endl;
    }


    // Test tour qui atteint l'objectif en 4 coups

    ArtificialPlayer j3;
    j3.jeu(source, arrive, graph, actions2);
    std::cout << "\nRéorganisation des actions de j3 : \n";
    for(RR::Robot::Move c : actions2) {
        std::cout << "Carte " << c << std::endl;
    }

    return 0;
}