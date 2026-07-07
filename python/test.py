import json

def test():
    return json.dumps({"result": "hello world"})

if __name__ == "__main__":
    print(test())