package main

import (
    "bufio"
    "fmt"
    "io/ioutil"
    "net/http"
    "os"
    "strings"
    "sync"
    "time"
)

type ESurvival int

const (
    REJECT ESurvival = -1 // 拒绝请求
    SURVIVE ESurvival = 1 // 存在
    DIED ESurvival = 0 // 没有存活
)

var (
    ua = []string{
        "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/81.0.4044.129 Safari/537.36,Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/27.0.1453.93 Safari/537.36",
        "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/81.0.4044.129 Safari/537.36,Mozilla/5.0 (Windows NT 6.2; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/30.0.1599.17 Safari/537.36",
        "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/81.0.4044.129 Safari/537.36,Mozilla/5.0 (X11; NetBSD) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/27.0.1453.116 Safari/537.36",
        "Mozilla/5.0 (Windows NT 6.2; WOW64) AppleWebKit/537.36 (KHTML like Gecko) Chrome/44.0.2403.155 Safari/537.36",
        "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US) AppleWebKit/533.20.25 (KHTML, like Gecko) Version/5.0.4 Safari/533.20.27",
        "Mozilla/5.0 (Windows NT 6.1; WOW64; rv:23.0) Gecko/20130406 Firefox/23.0",
        "Opera/9.80 (Windows NT 5.1; U; zh-sg) Presto/2.9.181 Version/12.00",
    }
)

func logo() {
    logo0 := `
aaa
`
    fmt.Println(logo0)
}

func fileInit() {
    f1, _ := os.Create("output.txt")
    f1.Close()
    f2, _ := os.Create("outerror.txt")
    f2.Close()
}

func scanLogger(wg *sync.WaitGroup, resultChan chan<- [4]interface{}, result [4]interface{}) {
    defer wg.Done()
    status := result[0].(ESurvival)
    code := result[1].(int)
    url := result[2].(string)
    length := result[3].(int)
    if status == SURVIVE {
        fmt.Printf("[+] 状态码为: %d 存活URL为: %s 页面长度为: %d\n", code, url, length)
    } else if status == DIED {
        fmt.Printf("[-] 状态码为: %d  无法访问URL为: %s\n", code, url)
    } else if status == REJECT {
        fmt.Printf("[-] URL为: %s 的目标积极拒绝请求，予以跳过！\n", url)
    }
    if status == SURVIVE || status == DIED {
        fileName := ""
        if status == SURVIVE {
            fileName = "output.txt"
        } else {
            fileName = "outerror.txt"
        }
        withOpenFile, _ := os.OpenFile(fileName, os.O_APPEND|os.O_CREATE|os.O_WRONLY, 0644)
        withOpenFile.WriteString(fmt.Sprintf("[%d]  %s\n", code, url))
    }
}

func survive(wg *sync.WaitGroup, url string, resultChan chan<- [4]interface{}) {
    defer wg.Done()
    header := map[string]string{"User-Agent": ua[rand.Intn(len(ua))]}
    client := &http.Client{
        Timeout: time.Duration(6 * time.Second),
    }
    resp, err := client.Get(url)
    if err != nil {
        resultChan <- [4]interface{}{REJECT, 0, url, 0}
        return
    }
    defer resp.Body.Close()
    if resp.StatusCode == 200 || resp.StatusCode == 403 {
        body, _ := ioutil.ReadAll(resp.Body)
        resultChan <- [4]interface{}{SURVIVE, resp.StatusCode, url, len(body)}
    } else {
        resultChan <- [4]interface{}{DIED, resp.StatusCode, url, 0}
    }
}

func getTask(fileName string) []string {
    var urls []string
    withOpenFile, err := os.Open(fileName)
    defer withOpenFile.Close()
    if err != nil {
        fmt.Println("打开文件失败！", err)
        return urls
    }
    scanner := bufio.NewScanner(withOpenFile)
    for scanner.Scan() {
        line := scanner.Text()
        if !strings.HasPrefix(line, "http") {
            line = "http://" + line
        }
        urls = append(urls, line)
    }
    return urls
}

func main() {
    logo()
    fileInit()
    fmt.Print("请输入目标TXT文件名\nFileName >>> ")
    var txtName string
    fmt.Scan(&txtName)
    fmt.Println("================开始读取目标TXT并批量测试站点存活================")
    urls := getTask(txtName)
    var wg sync.WaitGroup
    resultChan := make(chan [4]interface{}, len(urls))
    for _, url := range urls {
        wg.Add(1)
        go survive(&wg, url, resultChan)
        time.Sleep(200 * time.Millisecond)
    }
    go func() {
        wg.Wait()
        close(resultChan)
    }()
    for result := range resultChan {
        scanLogger(&wg, resultChan, result)
    }
    fmt.Println("检测结束!")
}
