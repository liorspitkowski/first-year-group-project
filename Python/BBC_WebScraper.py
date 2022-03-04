from bs4 import BeautifulSoup, SoupStrainer
import requests
import time
import random
import re
import sys

numberRegex = "^(((\d(⁄|/)\d)|(\d?(½|¼))|(\d+(\.)?\d*))(-((\d(⁄|/)\d)|(\d?(½|¼))|(\d+(\.)?\d*)))?)"

def getRecipeUrls():
    url = 'https://www.bbcgoodfood.com/search/recipes/page/'

    file = open("Python/list_of_recipes.txt", "a")

    for i in range (1, 21):

        urls = []

        pageUrl = url + str(i)
        reqs = requests.get(pageUrl)
        print(str(i) + " | Responce from " + pageUrl + " : " + str(reqs.status_code))
        soup = BeautifulSoup(reqs.text, 'html.parser')

        for link in soup.find_all('a'):
            recipeUrl = link.get('href')
            if (recipeUrl != None
                and "/recipes/" in recipeUrl
                and "/recipes/category/" not in recipeUrl
                and "/recipes/collection/" not in recipeUrl
                and recipeUrl != "/recipes/family-meals-easy-fish-pie-recipe"):

                if (len(urls) == 0 or recipeUrl != urls[-1]):
                    urls.append(recipeUrl);

        for u in urls:
            file.write(u + "\n")

        time.sleep(random.random())

    file.close()


def parsePages():

    file = open("Python/list_of_recipes.txt", "r")
    pages = file.read().split("\n")

    for page in pages:
        parsePage(page)


def parsePage(url):

    baseUrl = 'https://www.bbcgoodfood.com'

    reqs = requests.get(baseUrl+url)
    soup = BeautifulSoup(reqs.text, 'html.parser')

    title = soup.find("h1", "heading-1").get_text()
    #print("Title : " + title)

    portions = soup.find_all("div", "icon-with-text__children")
    #print("Portions : " + portions[2].get_text())

    prep = soup.find_all("time")
    #print("Prep : " + prep[0].get_text())
    #print("Cook : " + prep[1].get_text())

    dietry = soup.find_all("span", "terms-icons-list__text d-flex align-items-center")
    #print("Dietry:")
    for d in dietry:
        continue
        #print(" - " + d.get_text())

    ingredientlist = soup.find_all("section", "recipe__ingredients col-12 mt-md col-lg-6")
    ingredients = soup.find_all("li", "pb-xxs pt-xxs list-item list-item--separator")
    #print("Ingredients:")
    for i in ingredients:
        ingridient = i.get_text()
        parseIngredient(ingridient)

    instructions = soup.find_all("li", "pb-xs pt-xs list-item")
    #print("Method : ")
    for i in instructions:
        continue
        #print(i.get_text())


def parseIngredient(ingredient):
    global numberRegex
    #print("PARSING : " + ingredient)
    #return = [foodName, amount, unit, additionalInfo]

    split = ingredient.split(", ")
    additionalInfo = split[1] if (len(split) > 1) else ""
    foodInfo = split[0]
    if (foodInfo.find("(") != -1):
        i = foodInfo.find("(")
        if (len(additionalInfo) > 0):
            additionalInfo = foodInfo[i:] + ", " + additionalInfo
        else:
            additionalInfo = foodInfo[i:]
        foodInfo = foodInfo[0:i-1]
    amount=""
    unit=""

    #form x(g,ml,kg,l) food
    if (re.search(numberRegex + "(g|ml|kg|l|cm) .*$", foodInfo)):
        i=foodInfo.find(" ")
        splitUnit = separateUnit(foodInfo[0:i])
        amount = splitUnit[0]
        unit = splitUnit[1]
        foodName = foodInfo[i+1:]

    #form x/x or x½ or x (tsp,tbsp) food
    elif (re.search(numberRegex + "\s(tsp|tbsp)\s.*$", foodInfo)):
        i=foodInfo.find(" ")
        amount = foodInfo[0:i]
        i += 1
        while foodInfo[i] != " ":
            i += 1
        unit = foodInfo[len(amount):i]
        foodName = foodInfo[i+1:]

    #form x/x or x½ or x food
    elif (re.search(numberRegex + "\s.*$", foodInfo)):
        i=foodInfo.find(" ")
        amount = foodInfo[0:i]
        foodName = foodInfo[i+1:]
        if (foodName.find(" of ") != -1):
            split = foodName.split(" of ")
            foodName = split[1]
            unit = split[0]

    elif (re.search("^.* of .*$", foodInfo)):
        parts = foodInfo.split(" of ")
        amount = parts[0]
        foodName = parts[1]

    elif (re.search("^([a-zA-Z]|\s)*$", foodInfo)):
        foodName = foodInfo

    else:
        foodName = "failed"
        print(ingredient)

    foodName.replace("can ", "")
    if (foodName)
    result = [foodName.strip(), amount.strip(), unit.strip(), additionalInfo.strip()]
    print(result)
    return result


def replaceFractions(foodInfo):
    if (re.search("^½", foodInfo)):
        foodInfo.replace("½", "0.5")
        return foodInfo
    elif (re.search("^\d½", foodInfo)):
        foodInfo.replace("½", ".5")
        return foodInfo
    elif (re.search("^\d⁄\d", foodInfo)):
        return str(int(foodInfo[0]) / int(foodInfo[2])) + foodInfo[3:]


def separateUnit(unit):

    global numberRegex

    if (re.search(numberRegex + "(kg|ml)", unit)):
        return [unit[0:-2], unit[-2:]]
    elif (re.search(numberRegex + "(g|l)", unit)):
        return [unit[0:-1], unit[-1:]]
    elif (re.search(numberRegex, unit)):
        return [unit, ""]
    else:
        print("parse unit error : " + unit)
        return ["", ""]

#print(bool(re.search(numberRegex, "1⁄2 lemon, juiced")))
#parseIngredient("1/2 tsp cinnamon")
parsePage("/recipes/orecchiette-with-butter-beans-parsley-chilli-lemon")
#parsePages()