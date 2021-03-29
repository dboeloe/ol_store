import pdb
import pprint
import sys


if len(sys.argv) == 4:
  first_move = [abs(int(sys.argv[1])),abs(int(sys.argv[2])),abs(int(sys.argv[3]))]
else:
  print('Please specify all initial movement with 3 parameter with number for up, right, down')
  sys.exit()

def getPlayerPosition(board_game):
  playerPosition = None
  for y, row in enumerate(board_game):
    for x,col in enumerate(row):
      if col =='X':
        playerPosition = [y, x]

  return playerPosition

def intialMovePlayer(board_game, first_move, playerPosition):
  colPlayer = playerPosition[1]
  rowPlayer = playerPosition[0]
  maxCol = len(board_game[0]) - 1
  maxRow = len(board_game) - 1

  for i, steps in enumerate(first_move):
    if i == 0: #index 0 is UP
      operator = -1
      axis = 'row'
    elif i == 1: # Index 1 is RIGHT
      operator = 1
      axis = 'col'
    else: # DOWN
      operator = 1
      axis = 'row'

    for x in range(steps):
      if axis == 'row':
        _RowPlayer = rowPlayer + (operator * 1)
        if _RowPlayer < 0 or _RowPlayer > maxRow:
          print('hmm1')
          break

        if board_game[_RowPlayer][colPlayer] == '#':
          print('hmm2')
          break

        rowPlayer = _RowPlayer
      else:
        _ColPlayer = colPlayer + (operator * 1)
        if _ColPlayer < 0 or _ColPlayer > maxCol:
          print('hmm3')
          break

        if board_game[rowPlayer][_ColPlayer] == '#':
          print('hmm4')
          break

        colPlayer = _ColPlayer

  board_game[playerPosition[0]][playerPosition[1]] = '.'
  board_game[rowPlayer][colPlayer] = 'X'
  return [rowPlayer, colPlayer], board_game

def print_board(board_game):
  pretty_board = []
  probably_treasure = []
  for y, row in enumerate(board_game):
    for x,col in enumerate(row):
      if col == '.':
        board_game[y][x] = '$'
        probably_treasure.append([y,x])
    s = " "
    pretty_board.append([s.join(row)])


  return pretty_board, probably_treasure


board_game = [
  ['#', '#', '#','#','#','#','#','#'],
  ['#', '.', '.','.','.','.','.','#'],
  ['#', '.', '#','#','#','.','.','#'],
  ['#', '.', '.','.','#','.','#','#'],
  ['#', 'X', '#','.','.','.','.','#'],
  ['#', '#', '#','#','#','#','#','#']
]

playerPosition = getPlayerPosition(board_game)
playerPosition, board_game = intialMovePlayer(board_game, first_move, playerPosition)

pretty_board, list_treasure = print_board(board_game)
pp = pprint.PrettyPrinter(indent=4)
pp.pprint(print_board(board_game))
print('List probable treasure coordinate (col, row) format')
pp.pprint(list_treasure)
