//インポート
import java.util.ArrayList;
import java.util.List;

public class Test9{
	public static void main(String[] args){
		//ListにA,B,C,Dを追加
		List<String> alp = new ArrayList<String>();
		alp.add("A");
		alp.add("B");
		alp.add("C");
		alp.add("D");
		
		//Listから要素の個数分(4回)動かす(\"で " を文字列に)
		for(int i = 0; i < alp.size(); i++){
			//要素を取得し、4回分出力
			System.out.println("Listの" + i + "番目：\""+ alp.get(i) + "\"");
		}
	}
}