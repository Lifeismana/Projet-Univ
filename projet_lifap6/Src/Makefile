CXX      = g++

.DEFAULT_GOAL := all

SOURCES = 
SOURCES += board.cpp parc_graph_djik.cpp

OBJECTS = $(SOURCES:.cpp=.o)

CXXFLAGS += -g -Wall -pedantic
LDFLAGS +=

$(OBJECTS) : %.o : %.cpp
	$(CXX) -MMD $(CXXFLAGS) -c $< -o $@

CLEAN_OBJECTS = $(OBJECTS)
TARGETS = 

########## test_board ##########

TEST_BOARD_SOURCES = test_board.cpp
TEST_BOARD_OBJECTS = $(TEST_BOARD_SOURCES:.cpp=.o)

test_board : $(TEST_BOARD_OBJECTS) $(OBJECTS) $(HEADERS)
	$(CXX) $(TEST_BOARD_OBJECTS) $(OBJECTS) -o $@ $(LDFLAGS)
	
$(TEST_BOARD_OBJECTS): %.o : %.cpp
	$(CXX) -MMD $(CXXFLAGS) -c $< -o $@

all : test_board
TARGETS += test_board
CLEAN_OBJECTS += $(TEST_BOARD_OBJECTS)

########## app ##########

APP_SOURCES = app.cpp
APP_OBJECTS = $(APP_SOURCES:.cpp=.o)

app : $(APP_OBJECTS) $(OBJECTS) $(HEADERS)
	$(CXX) -pthread $(APP_OBJECTS) $(OBJECTS) -o $@ $(LDFLAGS)
	
$(APP_OBJECTS): %.o : %.cpp
	$(CXX) -MMD $(CXXFLAGS) -c $< -o $@

all : app
TARGETS += app
CLEAN_OBJECTS += $(APP_OBJECTS)

########## artificial_player ##########

ARTIFICIAL_PLAYER_SOURCES = artificial_player.cpp 
ARTIFICIAL_PLAYER_OBJECTS = $(ARTIFICIAL_PLAYER_SOURCES:.cpp=.o) 

artificial_player : $(ARTIFICIAL_PLAYER_OBJECTS) $(OBJECTS) $(HEADERS)
	$(CXX) -pthread $(ARTIFICIAL_PLAYER_OBJECTS) $(OBJECTS) -o $@ $(LDFLAGS)
	
$(ARTIFICIAL_PLAYER_OBJECTS): %.o : %.cpp
	$(CXX) -MMD $(CXXFLAGS) -c $< -o $@

all : artificial_player
TARGETS += artificial_player
CLEAN_OBJECTS += $(ARTIFICIAL_PLAYER_OBJECTS)


########## cleanup ##########
#del old name
# CLEAN_OBJECTS+= test


DEPS = $(CLEAN_OBJECTS:.o=.d)

clean:
	@rm -f $(DEPS) $(TARGETS) $(CLEAN_OBJECTS)

-include $(DEPS)
