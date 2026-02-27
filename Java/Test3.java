public class Test3{
	public static void main(String[] args){
		int a = 0;
		//sampleをインスタンス化
		Sample sample = new Sample();
		//値0をSample.java(クラス)へ渡す
		sample.print(a);
	}
}

class Sample{
	//Test3.javaから渡された 0 を受け取り 1 を加算、出力
	public void print(int a){
		System.out.println(a + 1);
	}
}
